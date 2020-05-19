<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\LikeObserver;

/**
 * @SWG\Definition(
 *      definition="Like",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="likeable_id",
 *          description="likeable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="likeable_type",
 *          description="likeable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
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
class Like extends BaseModel
{

    public $table = 'likes';
    public $morphClass = 'like';
    const morphClass = 'like';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'likeable_id',
        'likeable_type',
        'user_id',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'likeable_id' => 'integer',
        'likeable_type' => 'string',
        'user_id' => 'integer',
        'type' => 'string',
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
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'user_id' => 'required|exists:users,id|unique_with:likes, likeable_id, likeable_type',
        'likeable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'likeable_id' => 'required|poly_exists:likeable_type',
      ], $merge);
    }
    
    public static function boot()
    {
        parent::boot();
        Like::observe(new LikeObserver());
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    /**
     * Get all of the owning likeable models.
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
