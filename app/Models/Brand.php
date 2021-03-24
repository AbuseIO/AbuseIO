<?php

namespace AbuseIO\Models;

use AbuseIO\Traits\InstanceComparable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Account.
 *
 *
 * @property int    $id
 * @property string $name              fillable
 * @property string $company_name      fillable
 * @property string $logo              fillable
 * @property string $introduction_text fillable
 * @property bool   $systembrand       fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class Brand extends Model
{
    use SoftDeletes;
    use InstanceComparable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brands';

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
        'creator_id',
        'mail_custom_template',
        'mail_template_plain',
        'mail_template_html',
        'ash_custom_template',
        'ash_template',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'systembrand',
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
            'name'                => 'required|unique:brands,name',
            'company_name'        => 'required',
            'introduction_text'   => 'required',
            'logo'                => 'required|image|max:64',
            'creator_id'          => 'required|integer|exists:accounts,id',
            'systembrand'         => 'sometimes|required|uniqueflag:brands:systembrand',
            'mail_template_plain' => 'sometimes|required|bladetemplate',
            'mail_template_html'  => 'sometimes|required|bladetemplate',
            'ash_template'        => 'sometimes|required|bladetemplate',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\Brand $brand
     *
     * @return array $rules
     */
    public static function updateRules($brand)
    {
        $rules = [
            'name'                => 'required|unique:brands,name,'.$brand->id,
            'company_name'        => 'required',
            'introduction_text'   => 'required',
            'creator_id'          => 'required|integer|exists:accounts,id',
            'logo'                => 'sometimes|required|image|max:64',
            'systembrand'         => 'sometimes|required|uniqueflag:brands:systembrand',
            'mail_template_plain' => 'sometimes|required|bladetemplate',
            'mail_template_html'  => 'sometimes|required|bladetemplate',
            'ash_template'        => 'sometimes|required|bladetemplate',
        ];

        return $rules;
    }

    /*
     |--------------------------------------------------------------------------
     | Relationship Methods
     |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'creator_id');
    }

    /**
     * @return File
     */
    public static function getDefaultLogo()
    {
        $logo = new File(base_path('public/images/logo_150.png'), true);

        return $logo;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the owner of this brand.
     *
     * @return \AbuseIO\Models\Account
     */
    public function getCreatorAccountAttribute()
    {
        return $this->account;
    }

    /**
     * Returns the owner (Account) of this brand.
     *
     * @return \AbuseIO\Models\Account
     */
    public function getCreatorAttribute()
    {
        return $this->account;
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
        // early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        return self::find($model_id)->creator->is($account);
    }

    /**
     * Return the default Brand.
     *
     * @return mixed
     */
    public static function getSystemBrand()
    {
        return self::where('systembrand', true)->first();
    }

    /**
     * Return true when the current brand is the system default.
     *
     * @return bool
     */
    public function isSystemBrand()
    {
        return $this->systembrand;
    }

    /**
     * Check to see if we can delete the current brand
     * The brand can only be deleted, if it isn't linked to accounts anymore
     * and if it isn't the system brand.
     *
     * @return bool
     */
    public function canDelete()
    {
        $result = false;

        // Not linked to an account and not the system account
        if (count($this->accounts) == 0 && !$this->isSystemBrand()) {
            // we can delete the brand
            $result = true;
        }

        return $result;
    }

    /**
     * writes the logo to a tempfile and returns the filepath.
     *
     * @return string
     */
    public function getLogoPath()
    {
        $path = '';

        $path = tempnam(sys_get_temp_dir(), 'ABUSEIO');
        $logo = fopen($path, 'w');
        fwrite($logo, $this->logo);
        fclose($logo);

        return $path;
    }

    /**
     * Returns the default mail templates as defined in notification-mail.
     * If the config option can't be found, it will return null.
     *
     * @return null|array
     */
    public static function getDefaultMailTemplate()
    {
        return config('notifications.Mail.templates');
    }

    /**
     * Returns the default ASH template as a string or null on failure.
     *
     * @return null|string
     */
    public static function getDefaultASHTemplate()
    {
        $result = null;

        $path = view('ash')->getPath();
        $template = file_get_contents($path);

        if ($template !== false) {
            $result = $template;
        }

        return $result;
    }
}
