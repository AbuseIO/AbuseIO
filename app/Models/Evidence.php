<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Filesystem;
use PhpMimeMailParser\Parser as MimeParser;

/**
 * Class Evidence.
 * @package AbuseIO\Models
 * @property int $id
 * @property string $filename fillable
 * @property string $sender fillable
 * @property string $subject fillable
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 */
class Evidence extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evidences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'sender',
        'subject',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'filename'  => 'required|file',
            'sender'    => 'required|string',
            'subject'   => 'required|string',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the events for this evidence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('AbuseIO\Models\Event');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Return the raw evidence data
     * TODO: Need to fix json evidence. Needs to be treated the same as eml
     *
     * @return bool|string
     */
    public function getDataAttribute()
    {
        if (is_file($this->filename)) {

            $data = file_get_contents($this->filename);

            if (is_object(json_decode($data))) {
                // It's json data

                $jsondata = json_decode($data);

                return [
                    'headers' => [
                        'from'      => $this->sender,
                        'subject'   => $this->subject,
                    ],
                    'message'       => json_decode($data),
                    'files'         => [],
                ];
            } else {
                // It's a regular email, parse it!
                $fs = new Filesystem;
                $cacheDir = $this->getCacheDir();
                if (!$fs->isDirectory($cacheDir)) {
                    if (!$fs->makeDirectory($cacheDir, 0755, true)) {
                        Log::error(
                            get_class($this) . ': ' .
                            'Unable to create temp directory: ' . $cacheDir
                        );
                    }
                }
                $email = new MimeParser();
                $email->setPath($this->filename);

                $email->saveAttachments($cacheDir);

                return [
                    'headers' => [
                        'from'      => $email->getHeader('from'),
                        'subject'   => $email->getHeader('subject'),
                    ],
                    'message'       => $email->getMessageBody('text'),
                    'files'         => $email->getAttachments(),
                    'files_dir'     => $cacheDir,
                ];

            }
        }

        return false;
    }

    /**
     * Return the complete eml data from the evidence file
     * @return bool|string
     */
    public function getEmlAttribute()
    {
        if (is_file($this->filename)) {
            return file_get_contents($this->filename);
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    public function getCacheDir()
    {
        return "/tmp/abuseio/cache/evidence_{$this->id}/";
    }

    public function getAttachment($filename)
    {
        $email = new MimeParser();
        $email->setPath($this->filename);

        foreach ($email->getAttachments() as $attachment) {
            if ($attachment->getFilename() == $filename) {
                return $attachment;
            }
        }

        return false;
    }
}
