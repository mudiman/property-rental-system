<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="PropertyService",
 *      required={""},
 *     @SWG\Property(
 *          property="property_pro_entity_id",
 *          description="property_pro_entity_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *     @SWG\Property(
 *          property="user_id",
 *          description="user_id",
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
 *          property="service_id",
 *          description="service_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      )
 * )
 */
class PropertyService extends BaseModel
{
    use SoftDeletes;

    public $table = 'property_services';
    public $morphClass = 'property_service';
    const morphClass = 'property_service';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'property_pro_entity_id',
        'user_id',
        'property_id',
        'service_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'property_pro_entity_id' => 'integer',
        'service_id' => 'integer',
        'property_id' => 'integer',
        'user_id' => 'integer',
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
        'property_pro_entity_id' => 'required_without_all:user_id,property_id|exists:property_pros,id',
        'service_id' => 'required|exists:services,id',
        'user_id' => 'required_with:property_id|exists:users,id',
        'property_id' => 'required_with:user_id|exists:properties,id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function propertyPro()
    {
        return $this->belongsTo(\App\Models\PropertyPro::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
