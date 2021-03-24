<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use PhpMimeMailParser\Parser as MimeParser;
use Storage;

/**
 * Class Evidence.
 *
 * @property int    $id
 * @property string $filename   fillable
 * @property string $sender     fillable
 * @property string $subject    fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
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
            'filename' => 'required|file',
            'sender'   => 'required|string',
            'subject'  => 'required|string',
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
        return $this->hasMany(Event::class);
    }

    /**
     * Return the tickets that have this evidence in it's events.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Event::class, 'evidence_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Return the raw evidence data
     * TODO: Need to fix json evidence. Needs to be treated the same as eml.
     *
     * @return bool|string
     */
    public function getDataAttribute()
    {
        $tempFilesystem = Storage::disk('local_temp');

        if (Storage::exists($this->filename)) {
            $data = Storage::get($this->filename);

            if (is_object(json_decode($data))) {
                // It's json data

                return [
                    'headers' => [
                        'from'    => $this->sender,
                        'subject' => $this->subject,
                    ],
                    'message' => json_decode($data),
                    'files'   => [],
                ];
            } else {
                // It's a regular email, parse it!
                $cacheDir = $this->getCacheDir();

                if (!$tempFilesystem->exists($cacheDir)) {
                    if (!$tempFilesystem->makeDirectory($cacheDir)) {
                        Log::error(
                            get_class($this).': '.
                            'Unable to create temp directory: '.$cacheDir
                        );
                    }
                }

                $email = new MimeParser();
                $email->setText($data);

                foreach ($email->getAttachments() as $index => $attachment) {
                    $tempFilesystem->put("{$cacheDir}/{$attachment->getFilename()}", $attachment->getContent());
                    $fileSizes[$index] = strlen($email->getMessageBody('text'));
                }

                return [
                    'headers' => [
                        'from'    => $email->getHeader('from'),
                        'subject' => $email->getHeader('subject'),
                    ],
                    'message'   => $email->getMessageBody('text'),
                    'files'     => $email->getAttachments(),
                    'files_dir' => $cacheDir,
                ];
            }
        }

        return false;
    }

    /**
     * Return the complete eml data from the evidence file.
     *
     * @return bool|string
     */
    public function getEmlAttribute()
    {
        if (Storage::exists($this->filename)) {
            return Storage::get($this->filename);
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance.
     *
     * @param int                     $model_id
     * @param \AbuseIO\Models\Account $account
     *
     * @return bool
     */
    public static function checkAccountAccess($model_id, Account $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        // Get all tickets related to this evidence
        $tickets = self::find($model_id)->tickets;

        // If tickets ip or domain contact is the same as current account
        // then allow access to this evidence
        foreach ($tickets as $ticket) {
            if (($ticket->accountIp->is($account)) ||
                ($ticket->accountDomain->is($account))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return "evidence_{$this->id}";
    }

    /**
     * @param $filename
     *
     * @return bool
     */
    public function getAttachment($filename)
    {
        $data = Storage::get($this->filename);

        $email = new MimeParser();
        $email->setText($data);

        foreach ($email->getAttachments() as $attachment) {
            if ($attachment->getFilename() == $filename) {
                return $attachment;
            }
        }

        return false;
    }
}
