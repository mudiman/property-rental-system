<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;

class UserRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'email_verification_code',
        'email_verification_code_expiry',
        'forgot_password_verification_code',
        'forgot_password_verification_code_expiry',
        'sms_verification_code',
        'sms_verification_code_expiry',
        'password',
        'verified',
        'admin_verified',
        'token',
        'remember_token',
        'secret',
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
        'account_reference',
        'customer_reference',
        'timezone',
        'configuration',
        'data',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }
    
    public function sendRegistrationVerificationLink($model)
    {
      $this->generateNotificationObject('user.verify_registration', $model, config('business.admin.id'), $model->id)->notifySingleEmail();
    }
    
    public function sendRegistrationVerificationSMSCode($model)
    {
      $this->generateNotificationObject('user.verify_registration', $model, config('business.admin.id'), $model->id)->notifySingleSms();
    }
    
    public function sendWelcomeEmail($model)
    {
      $this->dispatchSingleNotification('user.welcome', $model, config('business.admin.id'), $model->id);
    }
    
    public function sendForgotPasswordLink($model)
    {
      $model->forgot_password_verification_code_expiry = Carbon::now()->addDay(config('business.registration.expiry'));
      $model->forgot_password_verification_code = str_random(30);
      $model->save();
      $this->dispatchSingleNotification('user.forgot_password', $model, config('business.admin.id'), $model->id);
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    $this->generateNotificationObject($notificationConfig, $model, $from, $to)->notify();
  }
  
  public function dispatchSingleNotification($notificationConfig, $model, $from, $to) {
    $this->generateNotificationObject($notificationConfig, $model, $from, $to)->notifySingle();
  }
  
  private function generateNotificationObject($notificationConfig, $model, $from, $to)
  {
    return new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'first_name' => $model->first_name,
        'username' => $model->username,
        'mobile' => $model->mobile,
        'user_id' => $to,
        'email' => $model->email,
        'role' => $model->role,
        'status' => $model->status,
        'email_verification_code' => $model->email_verification_code,
        'sms_verification_code' => $model->sms_verification_code,
        'forgot_password_verification_code' => $model->forgot_password_verification_code,
        'messageId' => $model->id,
        'messageType' => User::morphClass,
        'snapshot' => json_encode($model->toArray())
      ] );
  }

}
