<?php

namespace App\Models;

use App\Observers\ViewingObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Viewing",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="property_id",
 *          description="property_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="conducted_by",
 *          description="conducted_by",
 *          type="integer",
 *          format="int32"
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
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="checkin",
 *          description="checkin",
 *          type="boolean"
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
class Viewing extends BaseModel
{
    use SoftDeletes;

    public $table = 'viewings';
    public $morphClass = 'viewing';
    const morphClass = 'viewing';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    const AVAILABLE = 'available';
    const REARRANGE = 'rearrange';
    const CONFIRM = 'confirm';
    const PASSED = 'passed';
    const NOSHOW_TENANT = 'tenant_noshow';
    const NOSHOW_LANDLORD = 'landlord_noshow';
    const DONE = 'done';
    const CANCEL = 'cancel';
    
    
    const TYPE_NORMAL = 'normal';
    const TYPE_CUSTOM = 'custom';

    protected $dates = ['deleted_at', 'start_datetime', 'end_datetime', 'created_at', 'updated_at'];


    public $fillable = [
        'property_id',
        'conducted_by',
        'start_datetime',
        'end_datetime',
        'type',
        'status',
        'checkin'
    ];
    
    protected $attributes = [
        'status' => self::AVAILABLE,
        'type' => self::TYPE_NORMAL,
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'property_id' => 'integer',
        'conducted_by' => 'integer',
        'type' => 'string',
        'status' => 'string',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'checkin' => 'boolean',
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
    public static $rules = [
        'property_id' => 'required|exists:properties,id|unique_with:viewings, start_datetime,deleted_at,NULL',
        'start_datetime' => 'required|date_format:Y-m-dTH:i:sZ',
        'end_datetime' => 'required|date_format:Y-m-dTH:i:sZ',
        'conducted_by' => 'required|exists:users,id',
        'status' => "in:".Viewing::AVAILABLE
          .",".Viewing::CONFIRM
          .",".Viewing::NOSHOW_TENANT
          .",".Viewing::NOSHOW_LANDLORD
          .",".Viewing::DONE
          .",".Viewing::CANCEL,
        'type' => "in:".Viewing::TYPE_NORMAL
          .",".Viewing::TYPE_CUSTOM,
    ];
    
    
    public static function boot()
    {
        parent::boot();    
        Viewing::observe(new ViewingObserver());
    }
    
    public function setStartDatetime($datetime)
    {
      $this->start_datetime = $this->parseISODatetime($datetime);
    }
    
    public function setEndDatetime($datetime)
    {
      $this->end_datetime = $this->parseISODatetime($datetime);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function conductedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'conducted_by');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewingRequestAll()
    {
        return $this->hasMany(\App\Models\ViewingRequest::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewingRequests()
    {
        return $this->viewingRequestAll()->where('status', ViewingRequest::STATUS_REQUEST);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function confirmRequests()
    {
        return $this->viewingRequestAll()->where('status', ViewingRequest::STATUS_CONFIRM);
    }
    
    /**
     * All confirm request or landlord rearrange confirm requests
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function confirmOrRearrangeConfirmRequests()
    {
        return $this->viewingRequestAll()->whereIn('status', [ViewingRequest::STATUS_CONFIRM, ViewingRequest::STATUS_REARRANGE]);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function events()
    {
        return $this->morphMany(\App\Models\Event::class, 'eventable');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messages()
    {
        return $this->morphMany(\App\Models\Message::class, 'messageable');
    }
}
