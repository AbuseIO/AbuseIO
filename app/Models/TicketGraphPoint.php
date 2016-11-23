<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class TicketGraphPoint extends Model
{
    /**
     * Class TicketGraphPoint
     * 
     * @property integer id
     * @property date day_date
     * @property string class
     * @property string type
     * @property string status
     * @property integer count
     * @property string lifecycle
     * 
     */

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day_date',
        'class',
        'type',
        'status',
        'count',
        'lifecycle'
    ];

}
