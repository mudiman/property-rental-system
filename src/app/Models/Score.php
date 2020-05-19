<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\ScoreObserver;
use App\Support\Helper;

/**
 * @SWG\Definition(
 *      definition="Score",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="score_type_id",
 *          description="score_type_id",
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
 *          property="scoreable_id",
 *          description="scoreable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="scoreable_type",
 *          description="scoreable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="score",
 *          description="score",
 *          type="number",
 *          format="float"
 *      ),
  *      @SWG\Property(
 *          property="score_change",
 *          description="score_change",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="current",
 *          description="current",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="max",
 *          description="max",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="min",
 *          description="min",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="factor",
 *          description="factor",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="streak_count",
 *          description="streak_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="max_diff",
 *          description="max_diff",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
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
class Score extends BaseModel
{
    use SoftDeletes;

    public $table = 'scores';
    public $morphClass = 'score';
    const morphClass = 'score';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $attributes = [
      'status' => 'active',
      'score' => 0,
      'max' => 10,
      'min' => 0,
      'current' => 0,
    ];
    
    public $fillable = [
        'score_type_id',
        'user_id',
        'scoreable_id',
        'scoreable_type',
        'status',
        'score',
        'score_change',
        'current',
        'max',
        'min',
        'factor',
        'streak_count',
        'max_diff',
        'comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'score_type_id' => 'integer',
        'user_id' => 'integer',
        'scoreable_id' => 'integer',
        'scoreable_type' => 'string',
        'status' => 'string',
        'score' => 'float',
        'score_change' => 'float',
        'current' => 'float',
        'max' => 'float',
        'min' => 'float',
        'factor' => 'float',
        'streak_count' => 'integer',
        'max_diff' => 'float',
        'comment' => 'string',
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
        'score_type_id' => 'required|exists:score_types,id',
        'user_id' => 'required|exists:users,id',
        'scoreable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'scoreable_id' => 'required|poly_exists:scoreable_type',
        'score' => 'required|numeric|'.Helper::FLOAT_REGEX,
      ], $merge);
    }
    
    
    public static function boot()
    {
        parent::boot();    
        Score::observe(new ScoreObserver());
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function scoreType()
    {
        return $this->belongsTo(\App\Models\ScoreType::class);
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
    public function messages()
    {
        return $this->morphMany(\App\Models\Message::class, 'messageable');
    }
    
    /**
     * Get all of the owning scoreable models.
     */
    public function scoreable()
    {
        return $this->morphTo();
    }

}
