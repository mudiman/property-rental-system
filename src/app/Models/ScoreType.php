<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="ScoreType",
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
 *          property="category",
 *          description="category",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="roles",
 *          description="roles",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="min_percentage",
 *          description="min_percentage",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="max_percentage",
 *          description="max_percentage",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="weight",
 *          description="weight",
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
class ScoreType extends BaseModel
{
    use SoftDeletes;

    public $table = 'score_types';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    const CATEGORY_TRANSACTION = 'transaction';
    const CATEGORY_SERVICE = 'service';
    const CATEGORY_PROFILE = 'profile';
    const CATEGORY_CONDUCT = 'conduct';

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $attributes = [
      'max_percentage' => 10,
      'min_percentage' => 0,
      'weight' => 1,
    ];
    
    public $fillable = [
        'title',
        'category',
        'roles',
        'min_percentage',
        'max_percentage',
        'weight'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'category' => 'string',
        'roles' => 'string',
        'min_percentage' => 'float',
        'max_percentage' => 'float',
        'weight' => 'float',
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
      'title' => 'required|min:3',
      'weight' => 'required|regex:/^\d*(\.\d{1})?$/',
      'category' => "required|in:".ScoreType::CATEGORY_TRANSACTION
          .",".ScoreType::CATEGORY_SERVICE
          .",".ScoreType::CATEGORY_PROFILE 
          .",".ScoreType::CATEGORY_CONDUCT,
    ];
    
    public static function boot()
    {
      parent::boot();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function scores()
    {
        return $this->hasMany(\App\Models\Score::class);
    }
}
