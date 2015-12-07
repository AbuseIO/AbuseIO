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

    /*
     * Validation rules for this model being created
     *
     * @param  Model $domain
     * @return Array $rules
     */
    public function createRules($domain)
    {
        $rules = [
            'name'          => 'required|unique:domains',
            'contact_id'    => 'required|integer',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  Model $domain
     * @return Array $rules
     */
    public function updateRules($domain)
    {
        $rules = [
            'name'          => 'required|unique:domains,name,'. $domain->id,
            'contact_id'    => 'required|integer',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    public function contact()
    {

        return $this->belongsTo('AbuseIO\Models\Contact');

    }
}
