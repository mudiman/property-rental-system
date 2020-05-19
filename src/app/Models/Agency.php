<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\AgencyObserver;

/**
 * @SWG\Definition(
 *      definition="Agency",
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
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="commission",
 *          description="commission",
 *          type="number",
 *          format="float"
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
class Agency extends BaseModel
{
    use SoftDeletes;

    public $table = 'agencies';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'user_id',
        'payin_id',
        'name',
        'status',
        'commission'
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
        'name' => 'string',
        'status' => 'string',
        'commission' => 'float',
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
      'payin_id' => 'required|exists:payins,id',
      'name' => 'required|min:3',
      'commission' => 'required',
    ];
    
    public static function boot()
    {
        parent::boot();
        Agency::observe(new AgencyObserver());
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ownerPayin()
    {
        return $this->belongsTo(\App\Models\Payin::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agents()
    {
        return $this->hasMany(\App\Models\Agent::class);
    }
    
    public function getCommission($amount)
    {
        return $this->commission * $amount;
    }
}
