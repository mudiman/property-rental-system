<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;
use App\Observers\PropertyProObserver;

/**
 * @SWG\Definition(
 *      definition="PropertyPro",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="thread",
 *          description="thread",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="landlord_id",
 *          description="landlord_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="property_pro_id",
 *          description="property_pro_id",
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
 *          property="property_pro_payin_id",
 *          description="property_pro_payin_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="property_pro_sign_location",
 *          description="property_pro_sign_location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="landlord_sign_location",
 *          description="landlord_sign_location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="price_type",
 *          description="price_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="price",
 *          description="price",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
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
class PropertyPro extends BaseModel implements Stateful
{
    use SoftDeletes, StatefulTrait;

    public $table = 'property_pros';
    public $morphClass = 'property_pro';
    const morphClass = 'property_pro';
    
    const STATUS_REQUEST = 'request';
    const STATUS_ACCEPT = 'accept';
    const STATUS_REJECT = 'reject';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
      * Transaction States
      * @var array
      */
     protected $states = [
         PropertyPro::STATUS_REQUEST => ['initial' => true],
         PropertyPro::STATUS_ACCEPT => ['final' => true],
         PropertyPro::STATUS_REJECT => ['final' => true], 
     ];

     /**
      * Transaction State Transitions
      * @var array
      */
     protected $transitions = [
        'transition'.PropertyPro::STATUS_ACCEPT  => [
             'from' => PropertyPro::STATUS_REQUEST ,
             'to' => PropertyPro::STATUS_ACCEPT
         ],
         'transition'.PropertyPro::STATUS_REJECT  => [
             'from' => PropertyPro::STATUS_REQUEST ,
             'to' => PropertyPro::STATUS_REJECT
         ],
     ];
     
    public $fillable = [
        'thread',
        'landlord_id',
        'property_pro_id',
        'property_id',
        'property_pro_payin_id',
        'property_pro_sign_location',
        'property_pro_sign_datetime',
        'landlord_sign_location',
        'landlord_sign_datetime',
        'price_type',
        'price',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'landlord_id' => 'integer',
        'property_pro_id' => 'integer',
        'property_id' => 'integer',
        'property_pro_payin_id' => 'integer',
        'property_pro_sign_location' => 'string',
        'landlord_sign_location' => 'string',
        'property_pro_sign_datetime' => 'datetime',
        'landlord_sign_datetime' => 'datetime',
        'price_type' => 'string',
        'price' => 'float',
        'status' => 'string',
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
      'landlord_id' => 'required|exists:users,id',
      'property_pro_id' => 'required|exists:users,id',
      'property_id' => 'required|exists:properties,id',
      'service_id' => 'exists:services,id',
      'price' => 'required',
      'price_type' => 'required|exists:service_fee_types,title',
      'request_by' => 'required',
      'status' => "in:".PropertyPro::STATUS_REQUEST
          .",".PropertyPro::STATUS_ACCEPT
          .",".PropertyPro::STATUS_REJECT,
    ];
    
    public static function boot()
    {
        parent::boot();
        PropertyPro::observe(new PropertyProObserver());
    }
    
    public function getCommission($amount)
    {
      if ($this->price_type == ServiceFeeType::TYPE_FLAT) {
        return $this->price;
      } else if ($this->price_type == ServiceFeeType::TYPE_COMMISSION) {
        return $this->price * $amount;
      }
    }
    
    public function setPropertyProSignLocationAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'property_pro_sign_location');
    }
    
    public function getPropertyProSignLocationAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    public function setLandlordSignLocationAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'landlord_sign_location');
    }
    
    public function getLandlordSignLocationAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    public function isPropertyProSigned() {
      return isset($this->property_pro_sign_datetime) && isset($this->property_pro_sign_location);
    }
    
    public function isLandlordSigned() {
      return isset($this->landlord_sign_location) && isset($this->landlord_sign_datetime);
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
    public function propertyPro()
    {
        return $this->belongsTo(\App\Models\User::class, 'property_pro_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function landlord()
    {
        return $this->belongsTo(\App\Models\User::class, 'landlord_id');
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messages()
    {
        return $this->morphMany(\App\Models\Message::class, 'messageable');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payinPropertyPro()
    {
        return $this->belongsTo(\App\Models\Payin::class, 'property_pro_payin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyServices()
    {
        return $this->hasMany(\App\Models\PropertyService::class, 'property_pro_entity_id');
    }
    
    public function getInitialState() {
      return PropertyPro::STATUS_REQUEST;
    }

    public function getStateColumn() {
      return 'status';
    }
}
