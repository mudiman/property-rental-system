<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\ViewingRequestObserver;
use Acacha\Stateful\Contracts\Stateful;
use Acacha\Stateful\Traits\StatefulTrait;


/**
 * @SWG\Definition(
 *      definition="ViewingRequest",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="viewing_id",
 *          description="viewing_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="view_by_user",
 *          description="view_by_user",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="checkin",
 *          description="checkin",
 *          type="boolean"
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
 *      ),
 *      @SWG\Property(
 *          property="events",
 *          description="events",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Event")
 *      )
 * )
 */
class ViewingRequest extends BaseModel implements Stateful
{
    use SoftDeletes, StatefulTrait;

    public $table = 'viewing_requests';
    public $morphClass = 'viewing_request';
    const morphClass = 'viewing_request';
    
    const STATUS_REARRANGE = 'rearrange';
    const STATUS_REQUEST = 'request';
    const STATUS_CONFIRM = 'confirm';
    const STATUS_CANCEL = 'cancel';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'viewing_id',
        'view_by_user',
        'checkin',
        'status'
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'viewing_id' => 'integer',
        'view_by_user' => 'integer',
        'checkin' => 'boolean',
        'status' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
   * Transaction States
   * @var array
   */
    protected $states = [
      ViewingRequest::STATUS_REQUEST => ['initial' => true],
      ViewingRequest::STATUS_REARRANGE,
      ViewingRequest::STATUS_CONFIRM => ['final' => true, 'initial' => true]
    ];
  
    /**
     * Transaction State Transitions
     * @var array
     */
    protected $transitions = [
      'transition' . ViewingRequest::STATUS_CONFIRM => [
        'from' => [ViewingRequest::STATUS_REQUEST, ViewingRequest::STATUS_REARRANGE],
        'to' => ViewingRequest::STATUS_CONFIRM
      ],
      'transition' . ViewingRequest::STATUS_REARRANGE => [
        'from' => ViewingRequest::STATUS_REQUEST,
        'to' => ViewingRequest::STATUS_REARRANGE
      ],
      'transition' . ViewingRequest::STATUS_REQUEST => [
        'from' => ViewingRequest::STATUS_REARRANGE,
        'to' => ViewingRequest::STATUS_REQUEST
      ],
      'transition' . ViewingRequest::STATUS_CANCEL => [
        'from' => [ViewingRequest::STATUS_REQUEST, ViewingRequest::STATUS_REARRANGE, ViewingRequest::STATUS_CONFIRM],
        'to' => ViewingRequest::STATUS_CANCEL
      ],
    ];
  
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'viewing_id' => 'required|exists:viewings,id|unique_with:viewing_requests, view_by_user',
        'view_by_user' => 'required|exists:users,id',
        'status' => "required|in:".ViewingRequest::STATUS_REQUEST
          .",".ViewingRequest::STATUS_REARRANGE
          .",".ViewingRequest::STATUS_CANCEL
          .",".ViewingRequest::STATUS_CONFIRM,
    ];
    
    public static function boot()
    {
        parent::boot();    
        ViewingRequest::observe(new ViewingRequestObserver());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function viewByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'view_by_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function viewing()
    {
        return $this->belongsTo(\App\Models\Viewing::class);
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
    public function events()
    {
        return $this->morphMany(\App\Models\Event::class, 'eventable');
    }
    
    
     protected function afterTransitionconfirm() {
       
       if ($this->viewing->status != Viewing::CONFIRM) {
          $this->viewing->status = Viewing::CONFIRM;
          $this->viewing->save();
       }
     }
     
     protected function afterTransitionrearrange() {
       if ($this->viewing->status != Viewing::CONFIRM) {
          $this->viewing->status = Viewing::CONFIRM;
          $this->viewing->save();
       }
     }
     
     protected function beforeTransitionrequest() {
      if (count($this->viewing->confirmRequests) == 1) {  
        $this->viewing->status = Viewing::AVAILABLE;
        $this->viewing->save();
      }
     }
  
    public function getInitialState() {
      return in_array($this->status, [ViewingRequest::STATUS_REQUEST, ViewingRequest::STATUS_CONFIRM]) ? $this->status: ViewingRequest::STATUS_REQUEST;
    }

    public function getStateColumn() {
      return 'status';
    }
}
