<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use AbuseIO\Models\Account;

/**
 * Class Account
 * @package AbuseIO\Models
 *
 * @property integer $id guarded
 * @property string $name
 * @property string $company_name
 * @property string $logo
 * @property string $introduction_text
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Brand extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_name',
        'logo',
        'introduction_text',
        'account_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that cannot be changed
     *
     * @var array
     */
    protected $guarded  = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Validation rules for this model being created
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'name'              => 'required|unique:brands,name',
            'company_name'      => 'required',
            'introduction_text' => 'required',
            'logo'              => 'required'
        ];

        return $rules;
    }

    /**
     * return the default Brand
     *
     * @return mixed
     */
    public static function getDefault()
    {
        $brand = Brand::find(1);
        return $brand;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Brand $brand
     * @return array $rules
     */
    public static function updateRules($brand)
    {
        $rules = [
            'name'              => 'required|unique:brands,name,'. $brand->id,
            'company_name'      => 'required',
            'introduction_text' => 'required',
        ];

        return $rules;
    }

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
        if ($account->isSystemAccount() && $user->hasRole('admin')) {
            return true;
        }

        if ($account->brand->id == $this->id && $user->hasRole('admin')) {
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

        if ($file->getSize() > $maxsize) {
            array_push($messages, "Logo exceeding max size of 64kb");
        }

        $mimetype = $file->getMimeType();
        if (!preg_match('/^image/', $mimetype)) {
            array_push($messages, "Uploaded logo is not an image, its mimetype is: $mimetype");
        }

        return (empty($messages));
    }

    /**
     * Check to see if we can delete the current brand
     * The brand can only be deleted, if it isn't linked to accounts anymore
     * and if it isn't the default brand
     *
     * @return bool
     */
    public function canDelete()
    {
        $result = false;

        // not linked to an account and not the default account
        if (count($this->accounts) == 0 && !$this->isDefault())
        {
            // we can delete the brand
            $result = true;
        }

        return $result;
    }


    /**
     * @return Account
     */
    public function getCreatorAccountAttribute()
    {
        return $this->account;
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('AbuseIO\Models\Account');
    }
}
