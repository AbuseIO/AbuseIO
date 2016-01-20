<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domain
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property string $name
 * @property integer $contact_id
 * @property boolean $enabled
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Domain extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'domains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contact_id',
        'enabled'
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
            'name'          => 'required|unique:domains',
            'contact_id'    => 'required|integer',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Domain $domain
     * @return array $rules
     */
    public static function updateRules($domain)
    {
        $rules = [
            'name'          => 'required|unique:domains,name,'. $domain->id,
            'contact_id'    => 'required|integer',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    /**
     * Static method to check if the account has access to the model instance
     *
     * @param $model_id
     * @param $account
     * @return bool
     */
    public static function checkAccountAccess($model_id, $account)
    {
        // early return when we are in the system account
        if ($account->isSystemAccount())
            return true;

        $domain = Domain::find($model_id);
        return ($domain->contact->account->id == $account->id);
    }


    /**
     * Returns the contact for this domain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {

        return $this->belongsTo('AbuseIO\Models\Contact');

    }
}
