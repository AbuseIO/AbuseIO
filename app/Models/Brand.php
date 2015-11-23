<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package AbuseIO\Models
 *
 * @property integer $id
 * @property string $name
 * @property string $company_name
 * @property string $logo
 * @property string $introduction_text
 */
class Brand extends Model
{
    protected $table    = 'brands';

    protected $fillable = [
        'name',
        'company_name',
        'logo',
        'introduction_text',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Checks if the current user may edit the brand
     *
     * @param User $user
     * @return bool
     */
    public function mayEdit(User $user)
    {
        $account = $user->account;

        // System admin may always edit
        if ($account->isSystemAccount() && $user->hasRole('admin'))
        {
            return true;
        }

        if ($account->brand->id == $this->id && $user->hasRole('admin'))
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return true when the current brand is the system default
     *
     * @return bool
     */
    public function isDefault()
    {
        return ($this->id == 1);
    }

    /**
     * Check if the uploaded logo is a valid image, not bigger then
     * 64kb
     *
     * @param $file
     * @param $messages
     * @return bool
     */
    static public function checkUploadedLogo($file, &$messages)
    {
        // check for a valid image
        $maxsize = 64 * 1024; // 64kb max size of a database blob

        if ($file->getSize() > $maxsize)
        {
            array_push($messages, "Logo exceeding max size of 64kb");
        }

        $mimetype = $file->getMimeType();
        if (!preg_match('/^image/', $mimetype))
        {
            array_push($messages, "Uploaded logo is not an image, its mimetype is: $mimetype");
        }

        return (empty($messages));
    }


    /*
     |--------------------------------------------------------------------------
     | Relationship Methods
     |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accounts()
    {
        return $this->hasMany('AbuseIO\Models\Account');
    }
}

