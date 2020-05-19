<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;
use App\Observers\OfferObserver;
use App\Support\Helper;

/**
 * @SWG\Definition(
 *      definition="Offer",
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
 *          property="previous_offer_id",
 *          description="previous_offer_id",
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
 *          property="rent_per_month",
 *          description="rent_per_month",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="rent_per_week",
 *          description="rent_per_week",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="rent_per_night",
 *          description="rent_per_night",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="security_deposit_week",
 *          description="security_deposit_week",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="security_deposit_amount",
 *          description="security_deposit_amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="security_holding_deposit_amount",
 *          description="security_holding_deposit_amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="special_condition",
 *          description="special_condition",
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
class Offer extends BaseModel implements Stateful
{
  use SoftDeletes, StatefulTrait;

  public $table = 'offers';
  public $morphClass = 'offer';
  const morphClass = 'offer';
  
  protected $appends = ['rent'];
  
  const REQUEST = 'request';
  const ACCEPT = 'accept';
  const REJECT = 'reject';
  const COUNTER = 'counter';
  const COUNTERED = 'countered';
  const CANCEL = 'cancel';
  const ACCEPT_RENEWED = 'accept_renewed';
  const INITIAL_DEPOSIT_MADE = 'initialdeposit';
  
  const TYPE_SHORT = 'shorterm';
  const TYPE_MID = 'midterm';
  const TYPE_LONG = 'longterm';
  const TYPE_RENEW = 'renew';
  
  
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  protected $dates = [
    'offer_expiry',
    'holding_deposit_expiry',
    'checkin',
    'checkout',
    'deleted_at',
    'created_at',
    'updated_at'
  ];
  
  public $fillable = [
    'thread',
    'tenant_id',
    'property_id',
    'landlord_id',
    'property_pro_id',
    'previous_offer_id',
    'payout_id',
    'landlord_payin_id',
    'status',
    'type',
    'checkin',
    'checkout',
    'rent_per_month',
    'rent_per_week',
    'rent_per_night',
    'currency',
    'security_deposit_week',
    'security_deposit_amount',
    'security_holding_deposit_amount',
    'special_condition'
  ];
  
  protected $attributes = [
    'security_holding_deposit_amount' => 0,
    'security_deposit_amount' => 0,
    'security_deposit_week' => 0,
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'thread' => 'string',
    'tenant_id' => 'integer',
    'property_id' => 'integer',
    'landlord_id' => 'integer',
    'property_pro_id' => 'integer',
    'previous_offer_id' => 'integer',
    'payout_id' => 'integer',
    'landlord_payin_id' => 'integer',
    'status' => 'string',
    'type' => 'string',
    'checkin' => 'date',
    'checkout' => 'date',
    'rent_per_month' => 'float',
    'rent_per_week' => 'float',
    'rent_per_night' => 'float',
    'currency' => 'string',
    'security_deposit_week' => 'integer',
    'security_deposit_amount' => 'float',
    'security_holding_deposit_amount' => 'float',
    'special_condition' => 'string',
    'updated_by' => 'integer',
    'created_by' => 'integer',
    'deleted_by' => 'integer',
    'deleted_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Transaction States
   * @var array
   */
  protected $states = [
    Offer::COUNTER => ['initial' => true],
    Offer::REQUEST => ['initial' => true],
    Offer::REJECT,
    Offer::ACCEPT,
    Offer::INITIAL_DEPOSIT_MADE => ['final' => true]
  ];

  /**
   * Transaction State Transitions
   * @var array
   */
  protected $transitions = [
    'transition' . Offer::ACCEPT => [
      'from' => [Offer::REQUEST, Offer::COUNTER],
      'to' => Offer::ACCEPT
    ],
    'transition' . Offer::REJECT => [
      'from' => [Offer::REQUEST, Offer::COUNTER, Offer::INITIAL_DEPOSIT_MADE],
      'to' => Offer::REJECT
    ],
    'transition' . Offer::CANCEL => [
      'from' => [Offer::REQUEST, Offer::ACCEPT, Offer::COUNTER, Offer::INITIAL_DEPOSIT_MADE],
      'to' => Offer::CANCEL
    ],
    'transition' . Offer::INITIAL_DEPOSIT_MADE => [
      'from' => Offer::ACCEPT,
      'to' => Offer::INITIAL_DEPOSIT_MADE
    ],
    'transition' . Offer::COUNTER => [
      'from' => Offer::REQUEST,
      'to' => Offer::COUNTER
    ],
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
    'tenant_id' => 'required|exists:users,id',
    'landlord_id' => 'required|exists:users,id',
    'property_pro_id' => 'exists:users,id',
    'property_id' => 'required|exists:properties,id',
    'payout_id' => 'exists:payouts,id',
    'landlord_payin_id' => 'exists:payins,id',
    'previous_offer_id' => 'exists:offers,id',
    'checkin' => 'required|date_format:Y-m-d',
    'checkout' => 'required|date_format:Y-m-d',
//      'rent_per_month' => 'empty_if:rent_per_night,rent_per_week',
//      'rent_per_week' => 'empty_if:rent_per_month,rent_per_night',
//      'rent_per_night' => 'empty_if:rent_per_month,rent_per_week',
    'currency' => 'required',
    'rent_per_month' => 'required_without_all:rent_per_week,rent_per_night|numeric|'.Helper::FLOAT_REGEX,
    'rent_per_week' => 'required_without_all:rent_per_month,rent_per_night|numeric|'.Helper::FLOAT_REGEX,
    'rent_per_night' => 'required_without_all:rent_per_month,rent_per_week|numeric|'.Helper::FLOAT_REGEX,
    'security_deposit_week' => 'required_with:rent_per_week,rent_per_month|numeric',
    'security_deposit_amount' => 'required_with:security_deposit_week|numeric|'.Helper::FLOAT_REGEX,
    'security_holding_deposit_amount' => 'required_with:security_deposit_week|numeric|'.Helper::FLOAT_REGEX,
    
    'status' => "in:" . Offer::REQUEST
    . "," . Offer::ACCEPT
    . "," . Offer::REJECT
    . "," . Offer::CANCEL
    . "," . Offer::COUNTER
    . "," . Offer::INITIAL_DEPOSIT_MADE,
      
    'type' => "in:" . Offer::TYPE_SHORT
    . "," . Offer::TYPE_MID
    . "," . Offer::TYPE_LONG
    . "," . Offer::TYPE_RENEW
  ];
  
  protected static function boot() {
    parent::boot();
    Offer::observe(new OfferObserver());
  }
  
  public function getRentAttribute()
  {
    if (isset($this->rent_per_month)) return $this->rent_per_month;
    if (isset($this->rent_per_week)) return $this->rent_per_week;
    if (isset($this->rent_per_night)) return $this->rent_per_night;
  }
  
  public function updateSecurityDepositAmount()
  {
    if (isset($this->rent_per_month)) {
      $this->security_deposit_amount = ($this->rent_per_month * 12/52) * $this->security_deposit_week;
    }
    if (isset($this->rent_per_week)) {
      $this->security_deposit_amount = $this->rent_per_week * $this->security_deposit_week;
    }
    if (isset($this->rent_per_night)) {
      $this->security_deposit_amount = $this->rent_per_night * 7 * $this->security_deposit_week;
    }
  }
  
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function tenant() {
    return $this->belongsTo(\App\Models\User::class, 'tenant_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function previousOffer() {
    return $this->belongsTo(\App\Models\Offer::class, 'previous_offer_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function propertyPro() {
    return $this->belongsTo(\App\Models\User::class, 'property_pro_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function property() {
    return $this->belongsTo(\App\Models\Property::class, 'property_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function landlord() {
    return $this->belongsTo(\App\Models\User::class, 'landlord_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   * */
  public function payinLandlord() {
    return $this->belongsTo(\App\Models\Payin::class, 'landlord_payin_id');
  }
  
  /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payout()
    {
        return $this->belongsTo(\App\Models\Payout::class);
    }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   * */
  public function documents() {
    return $this->morphMany(\App\Models\Document::class, 'documentable');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   * */
  public function messages() {
    return $this->morphMany(\App\Models\Message::class, 'messageable');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   * */
  public function events() {
    return $this->morphMany(\App\Models\Event::class, 'eventable');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   * */
  public function reviews() {
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
   * */
  public function tenancy() {
    return $this->hasOne(\App\Models\Tenancy::class);
  }

  
  protected function afterTransitionrequest() {
  }
  
  /**
   * @return bool
   */
  protected function validateTransitionaccept() {
    
    if ($this->offer_expiry <= Carbon::now()) {
      $this->status = Offer::CANCEL;
      $this->save();
      $this->addErrorMessage('error', 'Offer acceptance time expired');
      return false;
    } else {
      return true;
    }
  }
  
  protected function afterTransitionaccept() {
    if ($this->type != Offer::TYPE_RENEW) {
      $this->holding_deposit_expiry = Carbon::now()->addHours(config('business.tenancy.holding_deposit_expiry'));
      $this->save();
    } else {
      $this->afterTransitioninitialdeposit();
    }
  }
  
  protected function afterTransitioninitialdeposit() {
    $tenancy = new Tenancy();
    $tenancy->fill($this->toArray());
    $tenancy->status = Tenancy::PRESIGN;
    $tenancy->offer_id = $this->id;
    $tenancy->checkin = $this->checkin;
    $tenancy->checkout = $this->checkout;
    
    $tenancy->actual_checkout = $this->checkout;
    $tenancy->due_date = $this->checkin;
    $tenancy->due_amount = $this->rent + $this->security_deposit_week - $this->security_holding_deposit_amount;
    $tenancy->sign_expiry = Carbon::now()->addHours(config('business.tenancy.sign_expiry'));
    
    $parentTenancy = Tenancy::whereIn('status', [Tenancy::START, Tenancy::PRE_NOTICE, Tenancy::NOTICE])
        ->where('tenant_id', $this->tenant_id)
        ->where('property_id', $this->property_id)->first();
    if (!empty($parentTenancy)) {
      $tenancy->parent_tenancy_id = $parentTenancy->id;
    }
    $tenancy->save();
  }
  
  public function getInitialState() {
    return in_array($this->status, [Offer::REQUEST, Offer::COUNTER]) ? $this->status: Offer::REQUEST;
  }

  public function getStateColumn() {
    return 'status';
  }
}
