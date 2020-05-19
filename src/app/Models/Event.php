<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @SWG\Definition(
 *      definition="Event",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="viewed",
 *          description="viewed",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="eventable_id",
 *          description="eventable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="eventable_type",
 *          description="eventable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="start_datetime",
 *          description="start_datetime",
 *          type="date-time"
 *      ),
 *      @SWG\Property(
 *          property="end_datetime",
 *          description="end_datetime",
 *          type="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_by",
 *          description="updated_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_by",
 *          description="created_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="deleted_by",
 *          description="deleted_by",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class Event extends BaseModel
{
    use SoftDeletes;

    public $table = 'events';
    public $morphClass = 'event';
    const morphClass = 'event';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    const VIEWING_CONFIRM = 'Viewing';
    
    const TENANCY_OFFER_EXPIRY = 'Tenancy offer expiry';
    const TENANCY_SIGN_EXPIRY = 'Tenancy Sign Expiry';
    const TENANCY_MOVEIN = 'Movein';
    const TENANCY_MOVEOUT = 'Moveout';


    protected $dates = ['deleted_at', 'start_datetime', 'end_datetime', 'created_at', 'updated_at'];


    public $fillable = [
        'title',
        'description',
        'user_id',
        'viewed',
        'eventable_id',
        'eventable_type',
        'start_datetime',
        'end_datetime'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'user_id' => 'integer',
        'viewed' => 'boolean',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'eventable_id' => 'integer',
        'eventable_type' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'title' => 'required',
        'description' => 'required',
        'user_id' => 'required|exists:users,id',
        'eventable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'eventable_id' => 'required|poly_exists:eventable_type',
      ], $merge);
    }
    
    public static function boot()
    {
        parent::boot();    
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    /**
     * Get all of the owning eventable models.
     */
    public function eventable()
    {
        return $this->morphTo();
    }
}
