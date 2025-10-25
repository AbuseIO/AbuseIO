<?php

namespace AbuseIO\Jobs;

use Carbon;
use Illuminate\Support\Str;
use Log;
use Storage;

/**
 * This EvidenceSave class handles the writing of evidence files to FS.
 *
 * Class EvidenceSave
 */
class EvidenceSave extends Job
{
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @param string $fileData
     *
     * @return string $fileName
     */
    public function save($fileData)
    {
        $datefolder = Carbon::now()->format('Ymd');
        $path = 'mailarchive/'.$datefolder;
        $fileName = Str::uuid()->toString().'.eml';
        $file = "{$path}/{$fileName}";

        umask(0007);

        if (!Storage::exists($path)) {
            // If a datefolder does not exist, then create it or die trying
            if (!Storage::makeDirectory($path, 0770)) {
                Log::error(
                    get_class($this).': '.
                    'Unable to create directory: '.$path
                );

                return false;
            }

            if (!Storage::exists($path)) {
                Log::error(
                    get_class($this).': '.
                    'Path vanished after write: '.$path
                );

                return false;
            }

            // Ensure owner/group only when mismatched, and handle permission errors gracefully
            $fullDirPath = storage_path()."/{$path}";
            $this->ensureOwnerGroup($fullDirPath);
        }

        if (Storage::exists($file)) {
            Log::error(
                get_class($this).': '.
                'File aready exists: '.$file
            );

            return false;
        }

        if (Storage::put($file, $fileData) === false) {
            Log::error(
                get_class($this).': '.
                'Unable to write file: '.$file
            );

            return false;
        }

        if (!Storage::exists($file)) {
            Log::error(
                get_class($this).': '.
                'File vanished after write: '.$file
            );

            return false;
        }

        // Ensure correct owner/group for file, if mismatched
        $fullFilePath = storage_path()."/{$file}";
        $this->ensureOwnerGroup($fullFilePath);

        return $file;
    }

    private function ensureOwnerGroup($fullPath)
    {
        $targetUser = config('app.user');
        $targetGroup = config('app.group');

        // Read current owner/group
        $currentUid = @fileowner($fullPath);
        $currentGid = @filegroup($fullPath);

        $currentUser = null;
        $currentGroup = null;

        if ($currentUid !== false && function_exists('posix_getpwuid')) {
            $pw = @posix_getpwuid($currentUid);
            $currentUser = is_array($pw) && isset($pw['name']) ? $pw['name'] : null;
        }

        if ($currentGid !== false && function_exists('posix_getgrgid')) {
            $gr = @posix_getgrgid($currentGid);
            $currentGroup = is_array($gr) && isset($gr['name']) ? $gr['name'] : null;
        }

        // Change group only if different
        if (!empty($targetGroup) && ($currentGroup === null || $currentGroup !== $targetGroup)) {
            if (!@chgrp($fullPath, $targetGroup)) {
                Log::warning(
                    get_class($this).': '.
                    'Unable to change group on '.$fullPath.' to '.$targetGroup.' (insufficient permissions?)'
                );
            }
        }

        // Change owner only if different
        if (!empty($targetUser) && ($currentUser === null || $currentUser !== $targetUser)) {
            if (!@chown($fullPath, $targetUser)) {
                Log::warning(
                    get_class($this).': '.
                    'Unable to change owner on '.$fullPath.' to '.$targetUser.' (insufficient permissions?)'
                );
            }
        }
    }
}
