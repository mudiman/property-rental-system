<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\PayinObserver;
use Carbon\Carbon;

/**
 * @SWG\Definition(
 *      definition="Payin",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="bank_name",
 *          description="bank_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="account_number",
 *          description="account_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="routing_number",
 *          description="routing_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="iban",
 *          description="iban",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="countryCode",
 *          description="countryCode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sort_code",
 *          description="sort_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bic",
 *          description="bic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ip",
 *          description="ip",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_name",
 *          description="first_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="gender",
 *          description="gender",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date_of_birth",
 *          description="date_of_birth",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="ssn",
 *          description="ssn",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="legal_name",
 *          description="legal_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tax_id",
 *          description="tax_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="locality",
 *          description="locality",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="postal_code",
 *          description="postal_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="region",
 *          description="region",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="entity_type",
 *          description="entity_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="nationality",
 *          description="nationality",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="personal_id_number",
 *          description="personal_id_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_gateway_identity_document",
 *          description="payment_gateway_identity_document",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_gateway_identity_document_id",
 *          description="payment_gateway_identity_document_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="billing_address",
 *          description="billing_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payin_data",
 *          description="payin_data",
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
 *          property="reference",
 *          description="reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="token",
 *          description="token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="verified",
 *          description="verified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="payment_gateway_response",
 *          description="payment_gateway_response",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="verification_response",
 *          description="verification_response",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="default",
 *          description="default",
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
class Payin extends BaseModel
{
    use SoftDeletes;

    public $table = 'payins';
    public $morphClass = 'payin';
    const morphClass = 'payin';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public static $FIRE_EVENTS = true;

    protected $dates = ['deleted_at', 'date_of_birth', 'created_at', 'updated_at'];


    public $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'routing_number',
        'currency',
        'iban',
        'countryCode',
        'sort_code',
        'bic',
        'ip',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'ssn',
        'address',
        'legal_name',
        'tax_id',
        'locality',
        'postal_code',
        'region',
        'entity_type',
        'nationality',
        'personal_id_number',
        'payment_gateway_identity_document',
        'payment_gateway_identity_document_id',
        'billing_address',
        'payin_data',
        'reference',
        'token',
        'default',
        'verification_response',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'bank_name' => 'string',
        'account_number' => 'string',
        'routing_number' => 'string',
        'currency' => 'string',
        'iban' => 'string',
        'countryCode' => 'string',
        'sort_code' => 'string',
        'bic' => 'string',
        'ip' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'gender' => 'string',
        'date_of_birth' => 'date',
        'ssn' => 'string',
        'address' => 'string',
        'legal_name' => 'string',
        'tax_id' => 'string',
        'locality' => 'string',
        'postal_code' => 'string',
        'region' => 'string',
        'entity_type' => 'string',
        'nationality' => 'string',
        'personal_id_number' => 'string',
        'payment_gateway_identity_document' => 'string',
        'payment_gateway_identity_document_id' => 'string',
        'billing_address' => 'string',
        'payin_data' => 'string',
        'smoor_reference' => 'string',
        'user_reference' => 'string',
        'reference' => 'string',
        'token' => 'string',
        'default' => 'boolean',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $attributes = [
      'verified' => false,
      'default' => true,
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
      'user_id' => 'required|exists:users,id',
      'entity_type' => 'required',
      'countryCode' => 'required',
      'email' => 'required|email',
      'currency' => 'required',
      'routing_number' => 'required',
      'address' => 'required',
      'account_number' => 'required',
      'locality' => 'required',
      'postal_code' => 'required',
      'date_of_birth' => 'required|date_format:Y-m-d|before:today',
      'first_name' => 'required',
      'last_name' => 'required',
      'personal_id_number' => 'required'
    ];
    
    
    public static function boot()
    {
        parent::boot();    
        Payin::observe(new PayinObserver());
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
    public function getCurrentMonthLandlordPayoutAttribute()
    {
        return $this->transactions()->where('status', Transaction::STATUS_DONE)->sum('landlord_commission');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function getCurrentMonthPropertyProPayoutAttribute()
    {
        return $this->transactions()->where('status', Transaction::STATUS_DONE)->sum('property_pro_commission');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function getNewPropertyAmountAttribute()
    {
        return $this->transactions()->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateTimeString())
            ->where('title', Transaction::TITLE_INITIAL_DEPOSIT)
            ->where('status', Transaction::STATUS_DONE)
            ->sum('amount');
    }
}
