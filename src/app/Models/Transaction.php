<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\TransactionObserver;
use App\Support\Helper;

/**
 * @SWG\Definition(
 *      definition="Transaction",
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
 *          property="payin_id",
 *          description="payin_id",
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
 *          property="parent_transaction_id",
 *          description="parent_transaction_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="transactionable_id",
 *          description="transactionable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="transactionable_type",
 *          description="transactionable_type",
 *          type="string"
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
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="smoor_commission",
 *          description="smoor_commission",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="payment_gateway_commission",
 *          description="payment_gateway_commission",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="landlord_commission",
 *          description="landlord_commission",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="agency_commission",
 *          description="agency_commission",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="property_pro_commission",
 *          description="property_pro_commission",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="transaction_data",
 *          description="transaction_data",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="transaction_reference",
 *          description="transaction_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="smoor_reference",
 *          description="smoor_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="indempotent_key",
 *          description="indempotent_key",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="dividen_done",
 *          description="dividen_done",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_message",
 *          description="payment_error_message",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_error_type",
 *          description="payment_error_type",
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
 *          property="refund_status",
 *          description="refund_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="refund_reference",
 *          description="refund_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="refund_response",
 *          description="refund_response",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="retries",
 *          description="retries",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="token_used",
 *          description="token_used",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="due_date",
 *          description="due_date",
 *          type="string",
 *          format="date"
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
class Transaction extends BaseModel
{
    use SoftDeletes;

    public $table = 'transactions';
    public $morphClass = 'transaction';
    const morphClass = 'transaction';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_NONE = 'none';
    const STATUS_START = 'start';
    const STATUS_FAILED = 'failed';
    const STATUS_ABORTED = 'aborted';
    const STATUS_REFUND = 'refund';
    const STATUS_DONE = 'done';
    
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    
    const TITLE_INITIAL_DEPOSIT = 'initial deposit';
    const DESC_INITIAL_DEPOSIT = 'Holdiing deposit for offer %s';
    const TITLE_FIRST_RENT = 'Rent and remaining security';
    const DESC_FIRST_RENT = "First rent for tenancy id %s with remain security";
    const TITLE_MONTHLY_RENT = 'monthly rent';
    const DESC_MONTHLY_RENT = "Monthly rent for tenancy id %s for month %s";
    
    const TITLE_LANDLORD_SECURITY_DEPOSIT = 'landlord securty deposity';
    const DESC_LANDLORD_SECURITY_DEPOSIT = 'landlord securty deposity for offer %s';
    const TITLE_DEBIT_MONTHLY_RENT = 'monthly rent payin';
    const DESC_DEBIT_MONTHLY_RENT = 'monthly rent payin for tenancy %s month %s';
    
    protected $dates = ['deleted_at', 'due_date', 'created_at', 'updated_at'];


    public $fillable = [
        'user_id',
        'payin_id',
        'payout_id',
        'parent_transaction_id',
        'transactionable_id',
        'transactionable_type',
        'title',
        'description',
        'type',
        'amount',
        'currency',
        'smoor_commission',
        'payment_gateway_commission',
        'landlord_commission',
        'agency_commission',
        'property_pro_commission',
        'status',
        'transaction_data',
        'smoor_reference',
        'refund_status',
        'due_date',
        'payment_error_message',
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'payin_id' => 'integer',
        'payout_id' => 'integer',
        'parent_transaction_id' => 'integer',
        'transactionable_id' => 'integer',
        'transactionable_type' => 'string',
        'title' => 'string',
        'description' => 'string',
        'type' => 'string',
        'amount' => 'float',
        'currency' => 'string',
        'smoor_commission' => 'float',
        'payment_gateway_commission' => 'float',
        'landlord_commission' => 'float',
        'agency_commission' => 'float',
        'property_pro_commission' => 'float',
        'status' => 'string',
        'transaction_data' => 'string',
        'transaction_reference' => 'string',
        'smoor_reference' => 'string',
        'indempotent_key' => 'string',
        'dividen_done' => 'boolean',
        'payment_error_message' => 'string',
        'payment_error_type' => 'string',
        'payment_error_code' => 'string',
        'payment_error_status' => 'string',
        'payment_error_param' => 'string',
        'payment_response' => 'string',
        'refund_status' => 'string',
        'refund_reference' => 'string',
        'refund_response' => 'string',
        'retries' => 'integer',
        'token_used' => 'string',
        'due_date' => 'date',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $attributes = [
      'type' => Transaction::TYPE_CREDIT,
      'status' => Transaction::STATUS_START,
      'refund_status' => Transaction::STATUS_NONE,
      'dividen_done' => false,
      'amount' => 0,
      'retries' => 0,
      'smoor_commission' => 0,
      'payment_gateway_commission' => 0,
      'property_pro_commission' => 0,
      'agency_commission' => 0,
      'landlord_commission' => 0,
    ];

    /**
     * Validation rules
     * @var array
     */
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'amount' => 'required',
        'type' => 'required',
        'user_id' => 'nullable|exists:users,id',
        'parent_transaction_id' => 'nullable|exists:transactions,id',
//        'payin_id' => 'nullable|exists:payin,id|required_without:payout_id',
//        'payout_id' => 'nullable|exists:payouts,id|required_without:payin_id',
        'smoor_commission' => 'numeric|'.Helper::FLOAT_REGEX,
        'payment_gateway_commission' => 'numeric|'.Helper::FLOAT_REGEX,
        'property_pro_commission' =>'numeric|'.Helper::FLOAT_REGEX,
        'agency_commission' => 'numeric|'.Helper::FLOAT_REGEX,
        'landlord_commission' => 'numeric|'.Helper::FLOAT_REGEX,
        'transactionable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'transactionable_id' => 'required|poly_exists:transactionable_type',
      ], $merge);
    }
    
    public static function boot()
    {
        parent::boot();    
        Transaction::observe(new TransactionObserver());
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payin()
    {
        return $this->belongsTo(\App\Models\Payin::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function payout()
    {
        return $this->belongsTo(\App\Models\Payout::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sourceTransaction()
    {
      return $this->BelongsTo(\App\Models\Transaction::class, 'transactions', 'parent_transaction_id', 'id');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function childTransactions()
    {
      return $this->HasMany(\App\Models\Transaction::class, 'parent_transaction_id');
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
     * Get all of the owning transactionable models.
     */
    public function transactionable()
    {
        return $this->morphTo();
    }
}
