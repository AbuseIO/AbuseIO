<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domain
 * @package AbuseIO\Models
 * @property string $name
 * @property integer $contact_id
 * @property boolean $enabled
 */
class Domain extends Model
{
    use SoftDeletes;

    protected $table    = 'domains';

    protected $fillable = [
        'name',
        'contact_id',
        'enabled'
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Domain $domain
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */ $domain)
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
     * Returns the contact for this domain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {

        return $this->belongsTo('AbuseIO\Models\Contact');

    }
}
