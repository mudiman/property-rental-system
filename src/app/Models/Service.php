<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Service",
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
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="area",
 *          description="area",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="lower_cap",
 *          description="lower_cap",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="upper_cap",
 *          description="upper_cap",
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
class Service extends BaseModel
{
    use SoftDeletes;

    public $table = 'services';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'title',
        'type',
        'description',
        'area',
        'lower_cap',
        'upper_cap'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'type' => 'string',
        'description' => 'string',
        'area' => 'string',
        'lower_cap' => 'float',
        'upper_cap' => 'float',
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
      'title' => 'required|min:2|unique_with:services, type',
      'type' => 'required|exists:service_types,title',
    ];
    
    public static function boot()
    {
      parent::boot();
    }

}
