<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @SWG\Definition(
 *      definition="Report",
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
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="reportable_id",
 *          description="reportable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="reportable_type",
 *          description="reportable_type",
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
class Report extends BaseModel
{
    use SoftDeletes;

    public $table = 'reports';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'by_user',
        'comment',
        'reportable_id',
        'reportable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'by_user' => 'integer',
        'comment' => 'string',
        'reportable_id' => 'integer',
        'reportable_type' => 'string',
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
        'reportable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'reportable_id' => 'required|poly_exists:reportable_type',
        'comment' => 'required',
      ], $merge);
    }
    
    
    public static function boot()
    {
        parent::boot();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function byUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'by_user');
    }
    
    /**
     * Get all of the owning reportable models.
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
