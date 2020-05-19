<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\ThreadObserver;


/**
 * @SWG\Definition(
 *      definition="Thread",
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
 *          property="status",
 *          description="status",
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
class Thread extends BaseModel
{
    use SoftDeletes;

    public $table = 'threads';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'title',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
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
        'title' => 'required|min:2'
    ];
    
    
    public static function boot()
    {
        parent::boot();    
        Thread::observe(new ThreadObserver());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLast()
    {
        return $this->hasMany(\App\Models\Message::class)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLastArchived()
    {
        return $this->hasMany(\App\Models\Message::class)->where('archived', true)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageOffers()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Offer::morphClass);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLastOffer()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Offer::morphClass)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageTenancys()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Tenancy::morphClass);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLastTenancy()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Tenancy::morphClass)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageViewings()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Viewing::morphClass);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLastViewing()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', Viewing::morphClass)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageViewingRequests()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', ViewingRequest::morphClass);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function messageLastmessageViewingRequest()
    {
        return $this->hasMany(\App\Models\Message::class)->where('messageable_type', ViewingRequest::morphClass)->orderBy('created_at')->limit(1);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function participants()
    {
        return $this->hasMany(\App\Models\Participant::class);
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function participantUsers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'participants', 'thread_id', 'user_id', 'thread_id')->withPivot('id');
    }
}
