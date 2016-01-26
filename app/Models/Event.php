<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property integer $ticket_id
 * @property integer $evidence_id
 * @property string $source
 * @property integer $timestamp
 * @property string $information
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Event extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'evidence_id',
        'source',
        'timestamp',
        'information'
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
            'ticket_id'             => 'required|integer',
            'evidence_id'           => 'required|integer',
            'source'                => 'required|string',
            'timestamp'             => 'required|timestamp',
            'information'           => 'required|json',
        ];

        return $rules;
    }

    /**
     * Return the evidence for this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evidences()
    {
        return $this->hasMany('AbuseIO\Models\Evidence', 'id', 'evidence_id');
    }

    /**
     * Return the evidence for this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evidence()
    {
        return $this->belongsTo('AbuseIO\Models\Evidence');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */
    /**
     * Mutates the seen attribute to a date format
     *
     * @return bool|string
     */
    public function getSeenAttribute()
    {
        return date(config('app.date_format').' '.config('app.time_format'), $this->attributes['timestamp']);
    }

    /**
     * Return a list of all known types, usefull for selections
     *
     * @return array $types
     */
    public static function getTypes()
    {
        $types = [];
        foreach (config('types.type') as $key) {
            $types[$key] = trans("types.type.{$key}.name");
        }
        return $types;
    }

    /**
     * Return a list of all known classifications, usefull for selections
     *
     * @return array $classifications
     */
    public static function getClassifications()
    {
        $classifications = [];
        foreach (trans('classifications') as $key => $class) {
            $classifications[$key] = $class['name'];
        }

        return $classifications;
    }

    /**
     * Return a list of all known statuses in the currently selected locale
     * @param  string $entity   Entity can be: 'abusedesk', 'contact', 'all'(default)
     * @return array  $statuses Array of statuses
     */
    public static function getStatuses($entity = 'all')
    {
        if (in_array($entity, ['abusedesk', 'contact', 'all'])) {
            if ($entity == 'all') {
                foreach (config('types.status') as $entity => $data) {
                    foreach (array_keys($data) as $key) {
                        $statuses[$entity][$key] = trans("types.status.{$entity}.{$key}.name");
                    }
                }
            } else {
                foreach (config("types.status.{$entitiy}") as $key) {
                    $statuses[$key] = trans("types.status.{$entitiy}.{$key}.name");
                }
            }
        }

        return $statuses;
    }
}
