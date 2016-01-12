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
     * @param  \AbuseIO\Models\Event $event
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */ $event)
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
     * Validation rules for this model being created using a form
     *
     * @param  \AbuseIO\Models\Event $event
     * @return array $rules
     */
    public static function formRules(/** @noinspection PhpUnusedParameterInspection */ $event)
    {
        $rules = [
            'source'                => 'required|string',
            'ip'                    => 'required|integer',
            'domain'                => 'sometimes|string',
            'uri'                   => 'sometimes|uri',
            'timestamp'             => 'required|timestamp',
            'information'           => 'required|json',
            'type_id'               => 'required|abusetype',
            'class_id'              => 'required|abuseclass',
            'evidenceFile'          => 'required|file'
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Event $event
     * @return array $rules
     */
    public static function updateRules(/** @noinspection PhpUnusedParameterInspection */ $event)
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
    public function getTypes()
    {
        $types = [ ];

        foreach (trans('types.type') as $id => $type) {
            $types[$id] = $type['name'];
        }

        return $types;
    }

    /**
     * Return a list of all known classifications, usefull for selections
     *
     * @return array $classifications
     */
    public function getClassifications()
    {
        $classifications = [ ];

        foreach (trans('classifications') as $id => $class) {
            $classifications[$id] = $class['name'];
        }

        return $classifications;
    }
}
