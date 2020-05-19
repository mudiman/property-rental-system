<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\ReviewObserver;

/**
 * @SWG\Definition(
 *      definition="Review",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="by_user",
 *          description="by_user",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="for_user",
 *          description="for_user",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="rating",
 *          description="rating",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="punctuality",
 *          description="punctuality",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="quality",
 *          description="quality",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="reviewable_id",
 *          description="reviewable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="reviewable_type",
 *          description="reviewable_type",
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
class Review extends BaseModel
{
    use SoftDeletes;

    public $table = 'reviews';
    public $morphClass = 'review';
    const morphClass = 'review';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'by_user',
        'for_user',
        'comment',
        'rating',
        'punctuality',
        'quality',
        'reviewable_id',
        'reviewable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'by_user' => 'integer',
        'for_user' => 'integer',
        'comment' => 'string',
        'rating' => 'float',
        'punctuality' => 'float',
        'quality' => 'float',
        'reviewable_id' => 'integer',
        'reviewable_type' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Validation rules
     * @var array
     */
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'by_user' => 'required|exists:users,id',
        'for_user' => 'required|exists:users,id',
        'reviewable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'reviewable_id' => 'required|poly_exists:reviewable_type',
        'comment' => 'required',
        'rating' => 'required|min:-5|max:5',
        'quality' => 'required|min:-5|max:5',
        'punctuality' => 'required|min:-5|max:5',
      ], $merge);
    }
    
    public static function boot()
    {
      parent::boot();
      Review::observe(new ReviewObserver());
    }
    
    public function getAverageAttribute()
    {
       return ($this->punctuality + $this->quality + $this->rating)/3;
    }
    
    public function getScoreAttribute()
    {
      return ($this->punctuality + $this->quality + $this->rating)/3;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function byUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'by_user');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function forUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'for_user');
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
    public function scoreable()
    {
        return $this->morphMany(\App\Models\Score::class, 'scoreable');
    }
    
    /**
     * Get all of the owning reviewable models.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }
}
