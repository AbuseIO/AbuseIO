<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event.
 *
 * @property int    $id
 * @property int    $ticket_id   fillable
 * @property int    $evidence_id fillable
 * @property string $source      fillable
 * @property int    $timestamp   fillable
 * @property string $information fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class Event extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

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
        'information',
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
            'ticket_id'   => 'required|integer|exists:tickets,id',
            'evidence_id' => 'required|integer|exists:evidences,id',
            'source'      => 'required|string',
            'timestamp'   => 'required|timestamp',
            'information' => 'required|json',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Return the evidence for this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evidence()
    {
        return $this->belongsTo(Evidence::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Mutates the seen attribute to a date format.
     *
     * @return bool|string
     */
    public function getSeenAttribute()
    {
        return date(config('app.date_format'), $this->attributes['timestamp']);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Return a list of all known types, usefull for selections.
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
     * Return a list of all known classifications, usefull for selections.
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
     * Return a list of all known statuses in the currently selected locale.
     *
     * @param string $entity Entity can be: 'abusedesk', 'contact', 'all'(default)
     *
     * @return array $statuses Array of statuses
     */
    public static function getStatuses($entity = 'all')
    {
        $statuses = [];
        if (in_array($entity, ['abusedesk', 'contact', 'all'])) {
            if ($entity == 'all') {
                foreach (config('types.status') as $entity => $data) {
                    foreach (array_keys($data) as $key) {
                        $statuses[$entity][$key] = trans("types.status.{$entity}.{$key}.name");
                    }
                }
            } else {
                foreach (config("types.status.{$entity}") as $key) {
                    $statuses[$key] = trans("types.status.{$entity}.{$key}.name");
                }
            }
        }

        return $statuses;
    }
}
