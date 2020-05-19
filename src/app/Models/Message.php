<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\MessageObserver;

/**
 * @SWG\Definition(
 *      definition="Message",
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
 *          property="thread_id",
 *          description="thread_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="message",
 *          description="message",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="messageable_id",
 *          description="messageable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="messageable_type",
 *          description="messageable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="snapshot",
 *          description="snapshot",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="archived",
 *          description="archived",
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
class Message extends BaseModel
{
    use SoftDeletes;

    public $table = 'messages';
    public $morphClass = 'message';
    const morphClass = 'message';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    const VIEWING_REQUEST_TITLE = 'New Viewing Request';
    const VIEWING_REQUEST_MESSAGE = 'New Viewing Request';
    const TENANCY_CREATE_TITLE = 'Tenancy create';
    const TENANCY_CREATE_MESSAGE = 'Tenancy create';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'by_user',
        'thread_id',
        'title',
        'message',
        'status',
        'messageable_id',
        'messageable_type',
        'snapshot',
        'archived'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'by_user' => 'integer',
        'thread_id' => 'integer',
        'title' => 'string',
        'message' => 'string',
        'status' => 'string',
        'messageable_id' => 'integer',
        'messageable_type' => 'string',
        'snapshot' => 'string',
        'archived' => 'boolean',
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
        'by_user' => 'exists:users,id',
        'message' => 'required',
        'messageable_type' => 'in:'.implode(",", array_keys(Relation::morphMap())),
        'messageable_id' => 'poly_exists:messageable_type',
      ] , $merge);
    }
    
    public static function boot()
    {
        parent::boot();
        Message::observe(new MessageObserver());
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function thread()
    {
        return $this->belongsTo(\App\Models\Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function byUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'by_user');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function forUsers()
    {
      $this->primaryKey = 'thread_id';
       return $this->belongsToMany(\App\Models\User::class, 'participants', 'thread_id', 'user_id', 'thread_id');

    }
    
    /**
     * Get all of the owning messageable models.
     */
    public function messageable()
    {
        return $this->morphTo();
    }
}
