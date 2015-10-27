<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package AbuseIO\Models
 *
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('AbuseIO\Models\Account');
    }
}

