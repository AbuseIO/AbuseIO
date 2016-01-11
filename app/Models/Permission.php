<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property integer $name
 * @property integer $description
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Permission extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
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
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * many-to-many relationship method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('AbuseIO\Models\Role');
    }
}
