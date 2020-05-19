<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;
use App\Observers\TenancyObserver;

/**
 * @SWG\Definition(
 *      definition="Tenancy",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="tenant_id",
 *          description="tenant_id",
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
 *          property="offer_id",
 *          description="offer_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="thread",
 *          description="thread",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="parent_tenancy_id",
 *          description="parent_tenancy_id",
 *          type="integer",
 *          format="int32"
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
 *          property="payout_id",
 *          description="payout_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="landlord_payin_id",
 *          description="landlord_payin_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mode",
 *          description="mode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="previous_status",
 *          description="previous_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="checkin",
 *          description="checkin",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="checkout",
 *          description="checkout",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="actual_checkin",
 *          description="actual checkin",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="actual_checkout",
 *          description="actual checkout",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="due_date",
 *          description="due_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="due_amount",
 *          description="due_amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="tenant_sign_location",
 *          description="tenant_sign_location value should be lat,long",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tenant_sign_datetime",
 *          description="tenant_sign_datetime",
 *          type="date-time"
 *      ),
 *      @SWG\Property(
 *          property="landlord_sign_location",
 *          description="landlord_sign_location value should be lat,long",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="landlord_sign_datetime",
 *          description="landlord_sign_datetime",
 *          type="date-time"
 *      ),
 *      @SWG\Property(
 *          property="special_condition",
 *          description="special_condition",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="landlord_notice_reminded",
 *          description="landlord_notice_reminded",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="tenant_notice_reminded",
 *          description="tenant_notice_reminded",
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
 *      ),
 *      @SWG\Property(
 *          property="parentTenancy",
 *          description="parentTenancy",
 *          type="object",
 *          ref="#/definitions/Tenancy")
 *      ),
 *      @SWG\Property(
 *          property="documents",
 *          description="documents",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Document")
 *      ),
 *      @SWG\Property(
 *          property="tenant",
 *          description="tenant",
 *          type="object",
 *          ref="#/definitions/User"
 *      ),
 *      @SWG\Property(
 *          property="landlord",
 *          description="landlord",
 *          type="object",
 *          ref="#/definitions/User"
 *      ),
 *      @SWG\Property(
 *          property="propertyPro",
 *          description="propertyPro",
 *          type="object",
 *          ref="#/definitions/User"
 *      ),
 *      @SWG\Property(
 *          property="messages",
 *          description="messages",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Message")
 *      ),
 *      @SWG\Property(
 *          property="events",
 *          description="events",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Event")
 *      ),
 *      @SWG\Property(
 *          property="offer",
 *          description="offer",
 *          type="object",
 *          ref="#/definitions/Offer")
 *      ),
 * )
 */
class Tenancy extends BaseModel implements Stateful
{
    use SoftDeletes, StatefulTrait;
    
    public $table = 'tenancies';
    public $morphClass = 'tenancy';
    const morphClass = 'tenancy';
    
    const PRESIGN = 'presign';
    const SIGNING_COMPLETE = 'signingcomplete';
    const START = 'start';
    const COMPLETE = 'complete';
    const PRE_NOTICE = 'prenotice';
    const NOTICE = 'notice';
    const CANCEL = 'cancel';
    const RENEWED = 'renewed';
    const ROLLING = 'rollover';
    
    const MODE_NORMAL = 'normal';
    const MODE_MANUAL = 'manual';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    /**
      * Transaction States
      * @var array
      */
     protected $states = [
         Tenancy::PRESIGN => ['initial' => true],
         Tenancy::SIGNING_COMPLETE,
         Tenancy::START,
         Tenancy::PRE_NOTICE,
         Tenancy::NOTICE,
         Tenancy::CANCEL => ['final' => true],
         Tenancy::COMPLETE => ['final' => true],
         Tenancy::RENEWED => ['final' => true]
     ];

    /**
     * Transaction State Transitions
     * @var array
     */
    protected $transitions = [
        'transition'.Tenancy::SIGNING_COMPLETE => [
            'from' => Tenancy::PRESIGN,
            'to' => Tenancy::SIGNING_COMPLETE
        ],
       'transition'.Tenancy::START => [
            'from' => Tenancy::SIGNING_COMPLETE,
            'to' => Tenancy::START
        ],
       'transition'.Tenancy::COMPLETE => [
            'from' => Tenancy::START,
            'to' => Tenancy::COMPLETE
        ],
        'transition'.Tenancy::RENEWED => [
            'from' => [Tenancy::PRE_NOTICE, Tenancy::NOTICE, Tenancy::COMPLETE, Tenancy::START],
            'to' => Tenancy::RENEWED
        ],
       'transition'.Tenancy::PRE_NOTICE => [
            'from' => [Tenancy::START],
            'to' => Tenancy::PRE_NOTICE
        ],
       'transition'.Tenancy::NOTICE => [
            'from' => [Tenancy::PRE_NOTICE, Tenancy::ROLLING, Tenancy::START],
            'to' => Tenancy::NOTICE
        ],
      'transition'.Tenancy::ROLLING => [
            'from' => Tenancy::START,
            'to' => Tenancy::NOTICE
        ],
      'transition'.Tenancy::CANCEL => [
            'from' => [Tenancy::PRESIGN, Tenancy::START],
            'to' => Tenancy::CANCEL
        ]
    ];
   
    protected $dates = [
      'sign_expiry',
      'checkin',
      'checkout',
      'actual_checkin',
      'actual_checkout',
      'due_date',
      'tenant_sign_datetime',
      'landlord_sign_datetime',
      'deleted_at',
      'created_at', 
      'updated_at'
    ];


    public $fillable = [
        'tenant_id',
        'property_id',
        'thread',
        'parent_tenancy_id',
        'landlord_id',
        'property_pro_id',
        'payout_id',
        'landlord_payin_id',
        'status',
        'mode',
        'type',
        'checkin',
        'checkout',
        'actual_checkin',
        'actual_checkout',
        'tenant_sign_location',
        'tenant_sign_datetime',
        'landlord_sign_location',
        'landlord_sign_datetime',
        'special_condition'
    ];
    
    protected $attributes = [
      'status' => Tenancy::PRESIGN,
      'mode' => Tenancy::MODE_NORMAL,
    ];
    
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tenant_id' => 'integer',
        'property_id' => 'integer',
        'offer_id' => 'integer',
        'thread' => 'string',
        'parent_tenancy_id' => 'integer',
        'landlord_id' => 'integer',
        'property_pro_id' => 'integer',
        'payout_id' => 'integer',
        'landlord_payin_id' => 'integer',
        'status' => 'string',
        'mode' => 'string',
        'previous_status' => 'string',
        'type' => 'string',
        'checkin' => 'date',
        'checkout' => 'date',
        'actual_checkin' => 'datetime',
        'actual_checkout' => 'datetime',
        'due_date' => 'date',
        'due_amount' => 'float',
        'tenant_sign_location' => 'string',
        'tenant_sign_datetime' => 'datetime',
        'landlord_sign_location' => 'string',
        'landlord_sign_datetime' => 'datetime',
        'special_condition' => 'string',
        'landlord_notice_reminded' => 'boolean',
        'tenant_notice_reminded' => 'boolean',
        'sign_expiry' => 'datetime',
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
      'tenant_id' => 'required|exists:users,id',
      'landlord_id' => 'exists:users,id',
      'property_pro_id' => 'exists:users,id',
      'payout' => 'exists:payouts,id',
      'landlord_payin_id' => 'exists:payins,id',
      'property_id' => 'required|exists:properties,id',
      'parent_tenancy_id' => 'required|exists:tenancies,id',
      'checkin' => 'date_format:Y-m-d',
      'checkout' => 'date_format:Y-m-d',
      'actual_checkin' => 'nullable|date_format:Y-m-d\TH:i:s\Z',
      'actual_checkout' => 'nullable|date_format:Y-m-d\TH:i:s\Z',
      
      'tenant_sign_location' => 'required_with:tenant_sign_datetime|regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/',
      'landlord_sign_location' => 'required_with:landlord_sign_datetime|regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/',
      
      'status' => "in:".Tenancy::PRESIGN
        .",".Tenancy::SIGNING_COMPLETE
        .",".Tenancy::START
        .",".Tenancy::PRE_NOTICE
        .",".Tenancy::COMPLETE
        .",".Tenancy::NOTICE
        .",".Tenancy::CANCEL
        .",".Tenancy::RENEWED,
      
      'type' => "in:".Offer::TYPE_SHORT
        .",".Offer::TYPE_MID
        .",".Offer::TYPE_LONG
    ];
    
    
    protected static function boot() {
      parent::boot();
      Tenancy::observe(new TenancyObserver());
    }
    
    public function setTenantSignLocationAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'tenant_sign_location');
    }
    
    public function getTenantSignLocationAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    public function setLandlordSignLocationAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'landlord_sign_location');
    }
    
    public function getLandlordSignLocationAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    public function isTenantSigned() {
      return isset($this->tenant_sign_location) && isset($this->tenant_sign_datetime);
    }
    
    public function isLandlordSigned() {
      return isset($this->landlord_sign_location) && isset($this->landlord_sign_datetime);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tenant()
    {
        return $this->belongsTo(\App\Models\User::class, 'tenant_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function offer()
    {
        return $this->belongsTo(\App\Models\Offer::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function parentTenancy()
    {
        return $this->belongsTo(\App\Models\Tenancy::class, 'parent_tenancy_id');
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
    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class, 'property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function landlord()
    {
        return $this->belongsTo(\App\Models\User::class,'landlord_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentTenant()
    {
        return $this->documents()->where('type', Document::TYPE_TENANT_SIGNATURE);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documentLandlord()
    {
        return $this->documents()->where('type', Document::TYPE_LANDLORD_SIGNATURE);
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
    public function payout()
    {
        return $this->belongsTo(\App\Models\Payout::class, 'payout_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payinLandlord()
    {
        return $this->belongsTo(\App\Models\Payin::class, 'landlord_payin_id');
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
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactions()
    {
        return $this->morphMany(\App\Models\Transaction::class, 'transactionable');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionFirstRent()
    {
        return $this->morphOne(\App\Models\Transaction::class, 'transactionable')->where('title', Transaction::TITLE_FIRST_RENT);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionLandlordSecurityDeposit()
    {
        return $this->morphOne(\App\Models\Transaction::class, 'transactionable')->where('title', Transaction::TITLE_LANDLORD_SECURITY_DEPOSIT);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionLastFailedTransactions()
    {
        return $this->transactions()->orderBy('due_date', 'dec')
            ->where('type', Transaction::TYPE_CREDIT)
            ->where('status', Transaction::STATUS_FAILED);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionCurrentMonthRent()
    {
        return $this->morphOne(\App\Models\Transaction::class, 'transactionable')
            ->whereBetween('due_date', array(Carbon::now()->startOfMonth()->toDateTimeString(), Carbon::now()->endOfMonth()->toDateTimeString()))
            ->where('type', Transaction::TYPE_CREDIT)
            ->where('title', Transaction::TITLE_MONTHLY_RENT);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactionsDue()
    {
        return $this->transactions()
            ->where('type', Transaction::TYPE_CREDIT)
            ->whereIn('status', [Transaction::STATUS_START, Transaction::STATUS_FAILED]);
    }
    
    /**
     * After tenancy start
     */
    protected function afterTransitioncomplete() {
    }

    public function getInitialState() {
      return Tenancy::PRESIGN;
    }

    public function getStateColumn() {
      return 'status';
    }
}
