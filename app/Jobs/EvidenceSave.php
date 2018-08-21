<?php

namespace AbuseIO\Jobs;

use Carbon;
use Log;
use Storage;
use Uuid;

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
        $fileName = Uuid::generate(4).'.eml';
        $file = "${path}/{$fileName}";

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

            // Temporally hack until we can do this with Storage::
            chgrp(storage_path()."/{$path}", config('app.group'));
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

        // Temporally hack until we can do this with Storage::
        chgrp(storage_path()."/{$file}", config('app.group'));

        return $file;
    }
}
