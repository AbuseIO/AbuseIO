<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Filesystem\Filesystem;
use Carbon;
use Uuid;
use Log;

/**
 * This EvidenceSave class handles the writing of evidence files to FS
 *
 * Class EvidenceSave
 */
class EvidenceSave extends Job implements SelfHandling
{

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @return string $fileName
     * @param string $fileData
     */
    public function save($fileData)
    {
        $filesystem = new Filesystem;
        $datefolder = Carbon::now()->format('Ymd');
        $path       = storage_path() . '/mailarchive/' . $datefolder . '/';
        $file       = Uuid::generate(4) . '.eml';
        $filename   = $path . $file;

        umask(0007);

        if (!$filesystem->isDirectory($path)) {
            // If a datefolder does not exist, then create it or die trying
            if (!$filesystem->makeDirectory($path, 0770)) {
                Log::error(
                    get_class($this) . ': ' .
                    'Unable to create directory: ' . $path
                );

                return false;
            }

            if (!is_dir($path)) {
                Log::error(
                    get_class($this) . ': ' .
                    'Path vanished after write: ' . $path
                );

                return false;
            }

            chgrp($path, config('app.group'));
        }

        if ($filesystem->isFile($filename)) {
            Log::error(
                get_class($this) . ': ' .
                'File aready exists: ' . $filename
            );

            return false;
        }

        if ($filesystem->put($filename, $fileData) === false) {
            Log::error(
                get_class($this) . ': ' .
                'Unable to write file: ' . $filename
            );

            return false;
        }

        if (!is_file($filename)) {
            Log::error(
                get_class($this) . ': ' .
                'File vanished after write: ' . $filename
            );

            return false;
        }

        chgrp($filename, config('app.group'));

        return $filename;
    }
}
