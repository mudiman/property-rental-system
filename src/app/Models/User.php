<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Auth;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={""},
 *      @SWG\Property(
 *          property="first_name",
 *          description="first_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="username",
 *          description="username",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="role",
 *          description="role",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="about",
 *          description="about",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="about_smoor_score_service",
 *          description="about_smoor_score_service",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="gender",
 *          description="gender",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="cordinate",
 *          description="cordinate",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="postcode",
 *          description="postcode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="location",
 *          description="location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="profession",
 *          description="profession",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mobile",
 *          description="mobile",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="qualification",
 *          description="qualification",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="arla_qualified",
 *          description="arla_qualified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="profile_picture",
 *          description="profile_picture",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_verification_code",
 *          description="email_verification_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="forgot_password_verification_code",
 *          description="forgot_password_verification_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sms_verification_code",
 *          description="sms_verification_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="verified",
 *          description="verified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="admin_verified",
 *          description="admin_verified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="token",
 *          description="token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="secret",
 *          description="secret",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="transaction_reputation",
 *          description="transaction_reputation",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="transaction_reputation_max",
 *          description="transaction_reputation_max",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="transaction_reputation_min",
 *          description="transaction_reputation_min",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="conduct_reputation",
 *          description="conduct_reputation",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="conduct_reputation_max",
 *          description="conduct_reputation_max",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="conduct_reputation_min",
 *          description="conduct_reputation_min",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="service_reputation",
 *          description="service_reputation",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="service_reputation_max",
 *          description="service_reputation_max",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="service_reputation_min",
 *          description="service_reputation_min",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="date_of_birth",
 *          description="date_of_birth",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="school",
 *          description="school",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="available_to_move_on",
 *          description="available_to_move_on",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="commission_charge",
 *          description="commission_charge",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="area_covered",
 *          description="area_covered",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="place_of_work",
 *          description="place_of_work",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="languages",
 *          description="languages",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="current_residence_postcode",
 *          description="current_residence_postcode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="current_residence_property_type",
 *          description="current_residence_property_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="current_residence_bedrooms",
 *          description="current_residence_bedrooms",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="current_residence_bathrooms",
 *          description="current_residence_bathrooms",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="current_contract_type",
 *          description="current_contract_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="current_contract_start_date",
 *          description="current_contract_start_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="current_contract_end_date",
 *          description="current_contract_end_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="current_rent_per_month",
 *          description="current_rent_per_month",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="employment_status",
 *          description="employment_status",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="previous_employer",
 *          description="previous_employer",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="experience_years",
 *          description="experience_years",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="previous_job_function",
 *          description="previous_job_function",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="recent_search_query",
 *          description="recent_search_query",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="extra_info",
 *          description="extra_info",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="push_notification_messages",
 *          description="push_notification_messages",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="push_notification_viewings",
 *          description="push_notification_viewings",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="push_notification_offers",
 *          description="push_notification_offers",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="push_notification_other",
 *          description="push_notification_other",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="email_notification_messages",
 *          description="email_notification_messages",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="email_notification_viewings",
 *          description="email_notification_viewings",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="email_notification_offers",
 *          description="email_notification_offers",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="email_notification_other",
 *          description="email_notification_other",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="text_notification_messages",
 *          description="text_notification_messages",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="text_notification_viewings",
 *          description="text_notification_viewings",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="text_notification_offers",
 *          description="text_notification_offers",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="text_notification_other",
 *          description="text_notification_other",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="account_reference",
 *          description="account_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="customer_reference",
 *          description="customer_reference",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="timezone",
 *          description="timezone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="configuration",
 *          description="configuration",
 *          type="string"
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
 *          property="devices",
 *          description="devices",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Device")
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
 *          property="landlordOffers",
 *          description="offer request from landlord to be pro only pro user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/PropertyPro")
 *      ),
 *      @SWG\Property(
 *          property="landlords",
 *          description="landlords how made offer requests only pro user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/User")
 *      ),
 *      @SWG\Property(
 *          property="likes",
 *          description="property likes",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Property")
 *      ),
 *      @SWG\Property(
 *          property="manages",
 *          description="properties manages only for pro user",
 *          type="object",
 *          ref="#/definitions/Property"
 *      ),
 *      @SWG\Property(
 *          property="myMessages",
 *          description="user messages",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Message")
 *      ),
 *      @SWG\Property(
 *          property="properties",
 *          description="my properties only for landlord",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Property")
 *      ),
 *      @SWG\Property(
 *          property="myReferences",
 *          description="my references",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Reference")
 *      ),
 *      @SWG\Property(
 *          property="myReviews",
 *          description="my reviews",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Review")
 *      ),
 *      @SWG\Property(
 *          property="payins",
 *          description="payins",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Payin")
 *      ),
 *      @SWG\Property(
 *          property="payouts",
 *          description="payouts",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Payout")
 *      ),
 *      @SWG\Property(
 *          property="propertyProOffers",
 *          description="offers from property pro only landlord user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/PropertyPro")
 *      ),
 *      @SWG\Property(
 *          property="propertyPros",
 *          description="property pros who have sent offers only landlord user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/User")
 *      ),
 *      @SWG\Property(
 *          property="references",
 *          description="references given",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Reference")
 *      ),
 *      @SWG\Property(
 *          property="reportedBy",
 *          description="reported by",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Report")
 *      ),
 *      @SWG\Property(
 *          property="reportedUser",
 *          description="reported User",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Report")
 *      ),
 *      @SWG\Property(
 *          property="reviewsRecieved",
 *          description="reviews recieved",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Review")
 *      ),
 *      @SWG\Property(
 *          property="tenancies",
 *          description="tenancies",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Tenancy")
 *      ),
 *      @SWG\Property(
 *          property="tenanciesAsLandlord",
 *          description="tenanciesAsLandlord only tenant user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Tenancy")
 *      ),
 *      @SWG\Property(
 *          property="tenanciesAsTenant",
 *          description="tenanciesAsLandlord only landlord user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Tenancy")
 *      ),
 *      @SWG\Property(
 *          property="transactions",
 *          description="transactions",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Transaction")
 *      ),
 *      @SWG\Property(
 *          property="userServices",
 *          description="user services",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/UserService")
 *      ),
 *      @SWG\Property(
 *          property="viewings",
 *          description="viewings only landlord pro user",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Viewing")
 *      ),
 *      @SWG\Property(
 *          property="viewingRequests",
 *          description="viewingRequests",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/ViewingRequest")
 *      ),
 *      @SWG\Property(
 *          property="events",
 *          description="events",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Event")
 *      ),
 *      @SWG\Property(
 *          property="scores",
 *          description="scores",
 *          type="array",
 *          @SWG\Items(ref="#/definitions/Score")
 *      )
 * )
 */
class User extends BaseModel
{
    use SoftDeletes, HasApiTokens;

    public $table = 'users';
    public $morphClass = 'User';
    const morphClass = 'user';

    const ROLE_TENANT = 'tenant';
    const ROLE_LANDLORD = 'landlord';
    const ROLE_PROPERTY_PRO = 'property_pro';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = [
      'password',
      'token',
      'remember_token',
      'secret',
      'email_verification_code',
      'forgot_password_verification_code',
      'sms_verification_code'
    ];

    protected $dates = [
        'deleted_at',
        'email_verification_code_expiry', 
        'date_of_birth', 'created_at',
        'updated_at', 'available_to_move_on',
        'current_contract_start_date',
        'current_contract_end_date',
        'forgot_password_verification_code_expiry',
        'sms_verification_code_expiry'
      ];


    public $fillable = [
        'first_name',
        'last_name',
        'username',
        'role',
        'about',
        'about_smoor_score_service',
        'gender',
        'email',
        'cordinate',
        'postcode',
        'address',
        'location',
        'profession',
        'mobile',
        'status',
        'qualification',
        'arla_qualified',
        'profile_picture',
        'password',
        'verified',
        'admin_verified',
        'transaction_reputation',
        'transaction_reputation_max',
        'transaction_reputation_min',
        'conduct_reputation',
        'conduct_reputation_max',
        'conduct_reputation_min',
        'service_reputation',
        'service_reputation_max',
        'service_reputation_min',
        'date_of_birth',
        'phone',
        'school',
        'available_to_move_on',
        'commission_charge',
        'area_covered',
        'place_of_work',
        'languages',
        'current_residence_postcode',
        'current_residence_property_type',
        'current_residence_bedrooms',
        'current_residence_bathrooms',
        'current_contract_type',
        'current_contract_start_date',
        'current_contract_end_date',
        'current_rent_per_month',
        'employment_status',
        'previous_employer',
        'experience_years',
        'previous_job_function',
        'recent_search_query',
        'extra_info',
        'currency',
        'push_notification_messages',
        'push_notification_viewings',
        'push_notification_offers',
        'push_notification_other',
        'email_notification_messages',
        'email_notification_viewings',
        'email_notification_offers',
        'email_notification_other',
        'text_notification_messages',
        'text_notification_viewings',
        'text_notification_offers',
        'text_notification_other',
        'timezone',
        'configuration',
        'data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'username' => 'string',
        'role' => 'string',
        'about' => 'string',
        'about_smoor_score_service' => 'string',
        'gender' => 'string',
        'email' => 'string',
        'cordinate' => 'string',
        'postcode' => 'string',
        'address' => 'string',
        'location' => 'string',
        'profession' => 'string',
        'mobile' => 'string',
        'status' => 'string',
        'qualification' => 'string',
        'arla_qualified' => 'boolean',
        'profile_picture' => 'string',
        'email_verification_code' => 'string',
        'email_verification_code_expiry' => 'datetime',
        'forgot_password_verification_code' => 'string',
        'forgot_password_verification_code_expiry' => 'datetime',
        'sms_verification_code' => 'string',
        'sms_verification_code_expiry' => 'datetime',
        'password' => 'string',
        'verified' => 'boolean',
        'admin_verified' => 'boolean',
        'token' => 'string',
        'remember_token' => 'string',
        'secret' => 'string',
        'transaction_reputation' => 'float',
        'transaction_reputation_max' => 'float',
        'transaction_reputation_min' => 'float',
        'conduct_reputation' => 'float',
        'conduct_reputation_max' => 'float',
        'conduct_reputation_min' => 'float',
        'service_reputation' => 'float',
        'service_reputation_max' => 'float',
        'service_reputation_min' => 'float',
        'date_of_birth' => 'date',
        'phone' => 'string',
        'school' => 'string',
        'available_to_move_on' => 'date',
        'commission_charge' => 'float',
        'area_covered' => 'string',
        'place_of_work' => 'string',
        'languages' => 'string',
        'current_residence_postcode' => 'string',
        'current_residence_property_type' => 'string',
        'current_residence_bedrooms' => 'integer',
        'current_residence_bathrooms' => 'integer',
        'current_contract_type' => 'string',
        'current_contract_start_date' => 'date',
        'current_contract_end_date' => 'date',
        'current_rent_per_month' => 'float',
        'employment_status' => 'boolean',
        'previous_employer' => 'string',
        'experience_years' => 'integer',
        'previous_job_function' => 'string',
        'recent_search_query' => 'string',
        'extra_info' => 'string',
        'currency' => 'string',
        'push_notification_messages' => 'boolean',
        'push_notification_viewings' => 'boolean',
        'push_notification_offers' => 'boolean',
        'push_notification_other' => 'boolean',
        'email_notification_messages' => 'boolean',
        'email_notification_viewings' => 'boolean',
        'email_notification_offers' => 'boolean',
        'email_notification_other' => 'boolean',
        'text_notification_messages' => 'boolean',
        'text_notification_viewings' => 'boolean',
        'text_notification_offers' => 'boolean',
        'text_notification_other' => 'boolean',
        'account_reference' => 'string',
        'customer_reference' => 'string',
        'timezone' => 'string',
        'configuration' => 'string',
        'data' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $attributes = [
      'transaction_reputation' => 5,
      'transaction_reputation_max' => 10,
      'transaction_reputation_min' => 0,
      'conduct_reputation' => 5,
      'conduct_reputation_max' => 10,
      'conduct_reputation_min' => 0,
      'service_reputation' => 5,
      'service_reputation_max' => 10,
      'service_reputation_min' => 0,
      'timezone' => 'Europe/London',
    ];
    
    protected $notificationMap = [
      "viewing_request" => "viewing"
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static function rules ($id = 0, $merge = []) {
      return array_merge([
        'cordinate' => 'nullable|regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/',
      ], $merge);
    }
    
    const PASSWORD_REGEX_RULE = '/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!\"#\$%&\'()*+,-\.:;<=>\?\@\[\]\^_`{|}~]).*$/';
    
    public static function boot()
    {
        parent::boot();
        User::observe(new UserObserver());
    }

    public function setEmailAttribute($email) {
      if (empty($email)) return;
      $this->attributes['email'] = strtolower($email);
    }
    
    public function setCordinateAttribute($cordinate) {
      parent::setCordinateMeta($cordinate, 'cordinate');
    }
    
    public function getCordinateAttribute($value) {
      return parent::getCordinateMeta($value);
    }
    
    public function checkRole($role)
    {
        return strpos($this->role, $role)  !== false;
    }
    
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function devices()
    {
        return $this->hasMany(\App\Models\Device::class);
    }
    
    
    public function messageSettingEnabled($channel, $messageType)
    {
      if ($messageType && isset($this->notificationMap[$messageType])) $messageType = $this->notificationMap[$messageType];
      
      if ($messageType && isset($this->{$channel.'_'.$messageType.'s'}))  {
        if ($this->{$channel.'_'.$messageType.'s'} == false) {
          return false;
        }
      } elseif($this->{$channel.'_other'} == false) {
        return false;
      }
      return true;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function currentDevice()
    {
        return $this->hasOne(\App\Models\Device::class)->where('token_id', Auth::user()->token()->id);
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
    public function feedback()
    {
        return $this->hasMany(\App\Models\Feedback::class);
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
    public function messages()
    {
        return $this->morphMany(\App\Models\Message::class, 'messageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function myMessages()
    {
        return $this->hasMany(\App\Models\Message::class, 'by_user');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function forMyMessages()
    {
        return $this->hasManyThrough(
          \App\Models\Message::class, \App\Models\Participant::class,
          'user_id', 'id', 'thread_id'
      );
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function participants()
    {
        return $this->hasMany(\App\Models\Participant::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agencies()
    {
        return $this->hasMany(\App\Models\Agency::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function myAgency()
    {
        return $this->BelongsTo(\App\Models\Agency::class, 'agents', 'user_id', 'agency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agent()
    {
        return $this->hasOne(\App\Models\Agent::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function propertyPros()
    {
      return $this->BelongsToMany(\App\Models\User::class, 'property_pros', 'landlord_id', 'property_pro_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function landlords()
    {
      return $this->BelongsToMany(\App\Models\User::class, 'property_pros', 'property_pro_id', 'landlord_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyProOffers()
    {
      return $this->hasMany(\App\Models\PropertyPro::class, 'property_pro_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function landlordOffers()
    {
      return $this->HasMany(\App\Models\PropertyPro::class, 'landlord_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function myProperties()
    {
        return $this->HasMany(\App\Models\Property::class, 'landlord_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function manages()
    { 
      return $this->hasManyThrough(
          \App\Models\Property::class, \App\Models\PropertyPro::class,
          'property_pro_id', 'id', 'property_id'
      );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function payins()
    {
        return $this->hasMany(\App\Models\Payin::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function payinDefault()
    {
        return $this->hasOne(\App\Models\Payin::class, 'user_id')->where('default', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function payouts()
    {
        return $this->hasMany(\App\Models\Payout::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function payoutDefault()
    {
        return $this->hasOne(\App\Models\Payout::class, 'user_id')->where('default', true);
    }
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function userLikes()
    {
        return $this->belongsToMany(\App\Models\User::class, 'likes', 'likeable_id', 'user_id')->where('likeable_type' , User::morphClass)->withPivot('id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/
    public function propertyLikes()
    {
        return $this->belongsToMany(\App\Models\Property::class, 'likes', 'likeable_id', 'user_id')->where('likeable_type' , Property::morphClass)->withPivot('id');
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
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function references()
    {
        return $this->hasMany(\App\Models\Reference::class, 'for_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function myReferences()
    {
        return $this->hasMany(\App\Models\Reference::class, 'by_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reportedBy()
    {
        return $this->hasMany(\App\Models\Report::class, 'by_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reportedUser()
    {
        return $this->morphMany(\App\Models\Report::class, 'reportable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function myReviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'by_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reviewsReceived()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function services()
    {
        return $this->hasMany(\App\Models\Service::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function statistics()
    {
        return $this->hasMany(\App\Models\Statistic::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tenanciesAsTenant()
    {
        return $this->hasMany(\App\Models\Tenancy::class, 'tenant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tenanciesAsLandlord()
    {
        return $this->hasMany(\App\Models\Tenancy::class, 'landlord_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function userServices()
    {
        return $this->hasMany(\App\Models\UserService::class, 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propertyServices()
    {
        return $this->hasMany(\App\Models\PropertyService::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewingRequests()
    {
        return $this->hasMany(\App\Models\ViewingRequest::class, 'view_by_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function viewings()
    {
        return $this->hasMany(\App\Models\Viewing::class, 'conducted_by');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function events()
    {
        return $this->hasMany(\App\Models\Event::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function scores()
    {
        return $this->hasMany(\App\Models\Score::class);
    }
}
