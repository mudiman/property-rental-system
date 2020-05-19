<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\PayoutObserver;
use Carbon\Carbon;

/**
 * @SWG\Definition(
 *      definition="Payout",
 *      required={""},
 *      @SWG\Property(
 *          property="payment_method",
 *          description="payment_method",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="holder_name",
 *          description="holder_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="card_number",
 *          description="card_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="expire_on_month",
 *          description="expire_on_month",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="expire_on_year",
 *          description="expire_on_year",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="expiry",
 *          description="expiry",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="security_code",
 *          description="security_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="country",
 *          description="country",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payout_data",
 *          description="payout_data",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payout_reference",
 *          description="payout_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="smoor_reference",
 *          description="smoor_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_reference",
 *          description="user_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="used",
 *          description="used",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="token",
 *          description="token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="default",
 *          description="default",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="invalid",
 *          description="invalid",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_type",
 *          description="payment_error_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_message",
 *          description="payment_error_message",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_code",
 *          description="payment_error_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_status",
 *          description="payment_error_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_param",
 *          description="payment_error_param",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_response",
 *          description="payment_response",
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
class Payout extends BaseModel
{
    use SoftDeletes;

    public $table = 'payouts';
    public $morphClass = 'payout';
    const morphClass = 'payout';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public static $FIRE_EVENTS = true;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'payment_method',
        'user_id',
        'holder_name',
        'card_number',
        'expire_on_month',
        'expire_on_year',
        'expiry',
        'security_code',
        'country',
        'payout_data',
        'payout_reference',
        'smoor_reference',
        'used',
        'token',
        'default',
        'invalid',
        'payment_error_type',
        'payment_error_message',
        'payment_error_code',
        'payment_error_status',
        'payment_error_param',
        'payment_response'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'payment_method' => 'string',
        'user_id' => 'integer',
        'holder_name' => 'string',
        'card_number' => 'string',
        'expire_on_month' => 'integer',
        'expire_on_year' => 'integer',
        'expiry' => 'date',
        'security_code' => 'string',
        'country' => 'string',
        'payout_data' => 'string',
        'payout_reference' => 'string',
        'smoor_reference' => 'string',
        'user_reference' => 'string',
        'used' => 'boolean',
        'token' => 'string',
        'default' => 'boolean',
        'invalid' => 'boolean',
        'payment_error_type' => 'string',
        'payment_error_message' => 'string',
        'payment_error_code' => 'string',
        'payment_error_status' => 'string',
        'payment_error_param' => 'string',
        'payment_response' => 'string',
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
      'user_id' => 'required|exists:users,id',
      'payment_method' => 'required|exists:payment_methods,title',
      'expire_on_month' => 'required',
      'expire_on_year' => 'required',
      'token' => 'required'
    ];
    
    
    public static function boot()
    {
        parent::boot();    
        Payout::observe(new PayoutObserver());
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function offers()
    {
        return $this->hasMany(\App\Models\Offer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messages()
    {
        return $this->morphMany(\App\Models\Message::class, 'messageable');
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
    public function getTransactionsSumAttribute()
    {
        return $this->transactions()->where('status', Transaction::STATUS_DONE)->sum('amount');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function getLastRentAttribute()
    {
       $lastRent = $this->transactions()
            ->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(1)->toDateTimeString())
            ->where('created_at', '<', Carbon::now()->startOfMonth()->toDateTimeString())
            ->where('title', Transaction::TITLE_MONTHLY_RENT)
            ->where('status', Transaction::STATUS_DONE)
            ->first(['amount']);
       if ($lastRent) {
         return $lastRent->amount;
       } else {
        return $lastRent;
       }
    }
    
    
}
