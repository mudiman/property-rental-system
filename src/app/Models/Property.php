<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Observers\PropertyObserver;
use Illuminate\Support\Facades\Auth;

/**
 * @SWG\Definition(
 *      definition="Property",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="landlord_id",
 *          description="landlord_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="profile_picture",
 *          description="profile_picture",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="reference",
 *          description="reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="summary",
 *          description="summary",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="letting_type",
 *          description="letting_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="property_type",
 *          description="property_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="room_type",
 *          description="room_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="room_suitable",
 *          description="room_suitable",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bathroom_type",
 *          description="bathroom_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="people_living",
 *          description="people_living",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="completion_phase",
 *          description="completion_phase",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="available_date",
 *          description="available_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="cordinate",
 *          description="cordinate value should be lat,long",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="postcode",
 *          description="postcode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="door_number",
 *          description="door_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="street",
 *          description="street",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city",
 *          description="city",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="verified",
 *          description="verified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="apartment_building",
 *          description="apartment_building",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="floors",
 *          description="floors",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="floor",
 *          description="floor",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="county",
 *          description="county",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="country",
 *          description="country",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="rent_per_month",
 *          description="rent_per_month",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="rent_per_week",
 *          description="rent_per_week",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="rent_per_night",
 *          description="rent_per_night",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="minimum_accepted_price",
 *          description="minimum_accepted_price",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="minimum_accepted_price_short_term_price",
 *          description="minimum_accepted_price_short_term_price",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="security_deposit_weeks",
 *          description="security_deposit_weeks",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="security_deposit_amount",
 *          description="security_deposit_amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="security_deposit_holding_amount",
 *          description="security_deposit_holding_amount",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="contract_length_months",
 *          description="contract_length_months",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="shortterm_rent_per_month",
 *          description="shortterm_rent_per_month",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="shortterm_rent_per_week",
 *          description="shortterm_rent_per_week",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="valuation_comment",
 *          description="valuation_comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="valuation_rating",
 *          description="valuation_rating",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="quick_booking",
 *          description="quick_booking",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="area_overview",
 *          description="area_overview",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="area_info",
 *          description="area_info",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="notes",
 *          description="notes",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="rules",
 *          description="rules",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="getting_around",
 *          description="getting_around",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="receptions",
 *          description="receptions",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="bedrooms",
 *          description="bedrooms",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="bathrooms",
 *          description="bathrooms",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="has_garden",
 *          description="has_garden",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="has_balcony_terrace",
 *          description="has_balcony_terrace",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="has_parking",
 *          description="has_parking",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="ensuite",
 *          description="ensuite",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="flatshares",
 *          description="flatshares",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="reviewed",
 *          description="reviewed",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="total_listing_view",
 *          description="total_listing_view",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="total_detail_view",
 *          description="total_detail_view",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="view_history",
 *          description="view_history",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="extra_info",
 *          description="extra_info",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="inclusive",
 *          description="inclusive",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="parent_property_ud",
 *          description="parent_property_ud",
 *          type="int32"
 *      ),
 *      @SWG\Property(
 *          property="data",
 *          description="data",
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
 *          property="documents",
 *          description="documents",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Document")
 *      ),
 *      @SWG\Property(
 *          property="images",
 *          description="images",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Image")
 *      ),
 *      @SWG\Property(
 *          property="messages",
 *          description="messages",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Message")
 *      ),
 *      @SWG\Property(
 *          property="propertyPros",
 *          description="propertyPros",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/User")
 *      ),
 *      @SWG\Property(
 *          property="propertyProRequests",
 *          description="propertyProRequests",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/PropertPro")
 *      ),
 *      @SWG\Property(
 *          property="likes",
 *          description="property likes",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Property")
 *      ),
 *      @SWG\Property(
 *          property="premiumlistings",
 *          description="premiumlistings",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/PremiumListing")
 *      ),
 *      @SWG\Property(
 *          property="reports",
 *          description="reports",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Report")
 *      ),
 *      @SWG\Property(
 *          property="reviews",
 *          description="reviews",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Review")
 *      ),
 *      @SWG\Property(
 *          property="statistics",
 *          description="statistics",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Statistic")
 *      ),
 *      @SWG\Property(
 *          property="tenancies",
 *          description="tenancies",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Tenancy")
 *      ),
 *      @SWG\Property(
 *          property="viewings",
 *          description="viewings",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Viewing")
 *      )
 * )
 */
class Property extends BaseModel implements Stateful
{
    use SoftDeletes, StatefulTrait;

    public $table = 'properties';
    public $morphClass = 'property';
    const morphClass = 'property';
    
    const STATUS_DRAFT = 'draft';
    const STATUS_LIVE = 'live';
    const STATUS_OCCUPIED = 'occupied';
    
        
    const FILLED_START = 'start';
    const FILLED_ADDRESS = 'address';
    const FILLED_TITLE = 'title';
    const FILLED_SUMMARY = 'summary';
    const FILLED_RENT = 'rent';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    /**
      * Transaction States
      * @var array
      */
     protected $states = [
         Property::STATUS_DRAFT => ['initial' => true],
         Property::STATUS_LIVE,
         Property::STATUS_OCCUPIED 
     ];

     /**
      * Transaction State Transitions
      * @var array
      */
     protected $transitions = [
        'transition'.Property::STATUS_DRAFT => [
             'from' => Property::STATUS_LIVE,
             'to' => Property::STATUS_DRAFT
         ],
         'transition'.Property::STATUS_LIVE => [
             'from' => Property::STATUS_DRAFT,
             'to' => Property::STATUS_LIVE
         ],
         'transition'.Property::STATUS_OCCUPIED  => [
             'from' => Property::STATUS_LIVE,
             'to' => Property::STATUS_OCCUPIED
         ]
     ];
   
    protected $dates = ['deleted_at', 'available_date', 'created_at', 'updated_at'];


    public $fillable = [
        'landlord_id',
        'profile_picture',
        'reference',
        'title',
        'summary',
        'letting_type',
        'property_type',
        'room_type',
        'room_suitable',
        'bathroom_type',
        'people_living',
        'status',
        'completion_phase',
        'available_date',
        'cordinate',
        'postcode',
        'door_number',
        'street',
        'city',
        'verified',
        'apartment_building',
        'floors',
        'floor',
        'county',
        'country',
        'currency',
        'rent_per_month',
        'rent_per_week',
        'rent_per_night',
        'minimum_accepted_price',
        'minimum_accepted_price_short_term_price',
        'security_deposit_weeks',
        'security_deposit_amount',
        'security_deposit_holding_amount',
        'contract_length_months',
        'shortterm_rent_per_month',
        'shortterm_rent_per_week',
        'valuation_comment',
        'valuation_rating',
        'quick_booking',
        'area_overview',
        'area_info',
        'notes',
        'rules',
        'getting_around',
        'receptions',
        'bedrooms',
        'bathrooms',
        'has_garden',
        'has_balcony_terrace',
        'has_parking',
        'ensuite',
        'flatshares',
        'reviewed',
        'total_listing_view',
        'total_detail_view',
        'view_history',
        'extra_info',
        'inclusive',
        'parent_property_id',
        'data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'landlord_id' => 'integer',
        'profile_picture' => 'string',
        'reference' => 'string',
        'title' => 'string',
        'summary' => 'string',
        'letting_type' => 'string',
        'property_type' => 'string',
        'room_type' => 'string',
        'room_suitable' => 'string',
        'bathroom_type' => 'string',
        'people_living' => 'integer',
        'status' => 'string',
        'completion_phase' => 'string',
        'available_date' => 'date',
        'cordinate' => 'string',
        'postcode' => 'string',
        'door_number' => 'string',
        'street' => 'string',
        'city' => 'string',
        'verified' => 'boolean',
        'apartment_building' => 'string',
        'floors' => 'integer',
        'floor' => 'integer',
        'county' => 'string',
        'country' => 'string',
        'currency' => 'string',
        'rent_per_month' => 'float',
        'rent_per_week' => 'float',
        'rent_per_night' => 'float',
        'minimum_accepted_price' => 'float',
        'minimum_accepted_price_short_term_price' => 'float',
        'security_deposit_weeks' => 'integer',
        'security_deposit_amount' => 'float',
        'security_deposit_holding_amount' => 'float',
        'contract_length_months' => 'integer',
        'shortterm_rent_per_month' => 'float',
        'shortterm_rent_per_week' => 'float',
        'valuation_comment' => 'string',
        'valuation_rating' => 'float',
        'quick_booking' => 'boolean',
        'area_overview' => 'string',
        'area_info' => 'string',
        'notes' => 'string',
        'rules' => 'string',
        'getting_around' => 'string',
        'receptions' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'has_garden' => 'boolean',
        'has_balcony_terrace' => 'boolean',
        'has_parking' => 'boolean',
        'ensuite' => 'boolean',
        'flatshares' => 'boolean',
        'reviewed' => 'boolean',
        'total_listing_view' => 'integer',
        'total_detail_view' => 'integer',
        'view_history' => 'string',
        'extra_info' => 'string',
        'inclusive' => 'string',
        'parent_property_id' => 'integer',
        'data' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $spatialFields = [
        'cordinate',
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'available_date' => 'date_format:Y-m-d',
        'letting_type' => 'required',
        'landlord_id' => 'required|exists:users,id',
        'property_pro_id' => 'exists:users,id',
        'cordinate' => 'regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/',
//        'property_type' => 'required',
//        'postcode' => 'required',
//        'door_number' => 'required',
//        'city' => 'required',
//        'street' => 'required',
//        'county' => 'required',
//        'country' => 'required',
      
        'property_type' => 'exists:property_room_types,title',
        'letting_type' => 'exists:letting_types,title',
        'bathroom_type' => 'exists:bathroom_types,title',
      
        'status' => "in:".Property::STATUS_DRAFT
          .",".Property::STATUS_LIVE
          .",".Property::STATUS_OCCUPIED,
    ];
    
    public static function boot()
    {
        parent::boot();
        Property::observe(new PropertyObserver());
    }
    
    public function setCordinateAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'cordinate');
    }
    
    public function getCordinateAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function landlord()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function images()
    {
        return $this->morphMany(\App\Models\Image::class, 'imageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyPros()
    {
      return $this->belongsToMany(
            \App\Models\User::class, 'property_pros',
            'property_id', 'property_pro_id'
        )->withPivot('id')->withPivot('price_type')->withPivot('price');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyProRequests()
    {
        return $this->hasMany(\App\Models\PropertyPro::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyProAcceptedRequests()
    {
        return $this->propertyProRequests()->where('status', PropertyPro::STATUS_ACCEPT);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function likeUsers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'likes', 'likeable_id', 'user_id')->where('likeable_type' , Property::morphClass)->withPivot('id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function liked()
    {
        return $this->morphMany(\App\Models\Like::class, 'likeable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function premiumlistings()
    {
        return $this->hasMany(\App\Models\PremiumListing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reports()
    {
        return $this->morphMany(\App\Models\Report::class, 'reportable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function statistics()
    {
      return $this->hasMany(\App\Models\Statistic::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewedBy()
    {
      return $this->belongsToMany(
            \App\Models\User::class, 'statistics'
        );
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function offers()
    {
        return $this->hasMany(\App\Models\Offer::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function userOffers()
    {
      $user_id = 1;
      if(Auth::check()) {
          $user_id = Auth::user()->id;
      }
      return $this->hasMany(\App\Models\Offer::class)->where(function ($query) use ($user_id) {
                  $query->orWhere('tenant_id', $user_id)
                      ->orWhere('landlord_id', $user_id);
                })->whereNotIn('status', [Offer::CANCEL, Offer::REJECT, Offer::ACCEPT_RENEWED])
                ->where('type', Offer::TYPE_RENEW)->orderBy('created_at', 'desc');
    }
    //cancel,reject, counter make an offer inactive
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tenancies()
    {
        return $this->hasMany(\App\Models\Tenancy::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewings()
    {
        return $this->hasMany(\App\Models\Viewing::class);
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
    
    /**
     * @return bool
     */
    protected function validateTransitionlive()
    {
      $validator = Validator::make($this->toArray(), [
        'title' => 'required',
        'cordinate' => 'required',
        'postcode' => 'required',
        'street' => 'required',
        'city' => 'required',
        'country' => 'required',
        'letting_type' => 'required',
        'property_type' => 'required',
        'room_suitable' => 'required',
        'rent_per_month' => 'required',
//        'rent_per_week' => 'required',
//        'rent_per_night' => 'required',
        'minimum_accepted_price' => 'required',
        'security_deposit_weeks' => 'required',
        'security_deposit_amount' => 'required',
        'security_deposit_holding_amount' => 'required',
        'contract_length_months' => 'required',
//        'shortterm_rent_per_month' => 'required',
//        'shortterm_rent_per_week' => 'required',
      ]);

      if ($validator->fails())
      {
        $this->addErrorMessage('error', implode(",",$validator->messages()->all()));
        return false;
      }
      return true;
    }
    
    /**
     * @return bool
     */
    protected function validateTransitionoccupied()
    {
        $valid = $this->validateTransitionlive()
          && $this->available_date > Carbon::now();
        
        if (!$valid) {
          $this->addErrorMessage('error', 'Property should be live and available_date should be greater than now');
        }
        return $valid;
    }
    
    /**
     * @return bool
     */
    protected function validateTransitiondraft()
    {
        return true;
    }
    
    protected function afterTransitionOccupied() {
        $this->available_date = Tenancy::where('property_id', $this->id)
            ->where('offer_status', Tenancy::TENANCY_START)->pluck(['checkout']);
    }
    
    protected function afterTransitionlive() {
        $this->available_date = Carbon::now();
    }
    
    public function getInitialState() {
      return Property::STATUS_DRAFT;
    }

    public function getStateColumn() {
      return 'status';
    }
}
