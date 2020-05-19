<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;

/**
 * Class UserTransformer
 * @package namespace App\Transformers;
 */
class UserTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model) {
      
      $response = [
        'id' => (int) $model->id,
        'first_name' => $model->first_name,
        'last_name' => $model->last_name,
        'username' => $model->username,
        'email' => $model->email,
        'about' => $model->about,
        'gender' => $model->gender,
        'postcode' => $model->postcode,
        'cordinate' => $model->cordinate,
        'date_of_birth' => $this->formatDate($model->date_of_birth),
        'mobile' => $model->mobile,
        'address' => $model->address,
        'role' => $model->role,
        'status' => $model->status,
        'profile_picture' => $model->profile_picture,
        'phone' => $model->phone,
        'school' => $model->school,
        'email_verification_code_expiry' => $this->formatDateTime($model->email_verification_code_expiry),
        'profession' => $model->profession,
        'available_to_move_on' => $this->formatDate($model->available_to_move_on),
        'qualification' => $model->qualification,
        'location' => $model->location,
        'languages' => $model->languages,
        'verified' => $model->verified,
        'admin_verified' => $model->admin_verified,
        'timezone' => $model->timezone,
       
        'current_residence_postcode' => $model->current_residence_postcode,
        'current_residence_property_type' => $model->current_residence_property_type,
        'current_residence_bedrooms' => $model->current_residence_bedrooms,
        'current_residence_bathrooms' => $model->current_residence_bathrooms,
        
        'current_contract_type' => $model->current_contract_type,
        'current_contract_start_date' => $this->formatDate($model->current_contract_start_date),
        'current_contract_end_date' => $this->formatDate($model->current_contract_end_date),
        'current_rent_per_month' => $this->formatAmount($model->current_rent_per_month),
        
        'currency' => $model->currency,
        'push_notification_messages' => boolval($model->push_notification_messages),
        'push_notification_viewings' => boolval($model->push_notification_viewings),
        'push_notification_offers' => boolval($model->push_notification_offers),
        'push_notification_other' => boolval($model->push_notification_other),
        'email_notification_messages' => boolval($model->email_notification_messages),
        'email_notification_viewings' => boolval($model->email_notification_viewings),
        'email_notification_offers' => boolval($model->email_notification_offers),
        'email_notification_other' => boolval($model->email_notification_other),
        'text_notification_messages' => boolval($model->text_notification_messages),
        'text_notification_viewings' => boolval($model->text_notification_viewings),
        'text_notification_offers' => boolval($model->text_notification_offers),
        'text_notification_other' => boolval($model->text_notification_other),
        
        
        'transaction_reputation' => (float) $model->transaction_reputation,
        'transaction_reputation_max' => (float) $model->transaction_reputation_max,
        'transaction_reputation_min' => (float) $model->transaction_reputation_min,
        'conduct_reputation' => (float) $model->conduct_reputation,
        'conduct_reputation_min' => (float) $model->conduct_reputation_max,
        'conduct_reputation_min' => (float) $model->conduct_reputation_min,
        'service_reputation' => (float)$model->service_reputation,
        'service_reputation_max' => (float)$model->service_reputation_max,
        'service_reputation_min' => (float)$model->service_reputation_min,
        
        
        'recent_search_query' => $model->recent_search_query,
        
        'extra_info' => $model->extra_info,
        'configuration' => $model->configuration,
        
        // property pro fields
        'about_smoor_score_service' => $model->about_smoor_score_service,
        'arla_qualified' => boolval($model->arla_qualified),
        'area_covered' => $model->area_covered,
        'place_of_work' => $model->place_of_work,
        'commission_charge' => $model->commission_charge,

        'previous_employer' => $model->previous_employer,
        'employment_status' => $model->employment_status,
        'experience_years' => $model->experience_years,
        'previous_job_function' => $model->previous_job_function,
        
        'images' => $model->images()->limit(10)->offset(0)->orderBy('created_at')->get($this->imageBasicFieldList()),
        'documents' => $model->documents()->limit(10)->offset(0)->orderBy('created_at')->get($this->documentBasicFieldList()),
        'devices' => $model->devices()->limit(10)->offset(0)->orderBy('created_at')->get($this->deviceBasicFieldList()),
        'references' => $model->references()->limit(10)->offset(0)->orderBy('created_at')->get($this->referenceBasicFieldList()),
        'viewing_requests' => $model->viewingRequests()->limit(10)->offset(0)->orderBy('created_at')->get($this->viewingRequestBasicFieldList()),
        'services' => $model->services()->limit(10)->offset(0)->orderBy('created_at')->get($this->serviceBasicFieldList()),
        
        'propertyLikes' => $model->propertyLikes()->limit(10)->offset(0)->get($this->propertyBasicFieldList()), 
        'userLikes' => $model->userLikes()->limit(10)->offset(0)->get($this->userBasicFieldList()),
        'liked' => $model->liked()->limit(10)->offset(0)->get(),
        
      ] + $this->transformDefault($model);
      
      if ($model->checkRole(User::ROLE_PROPERTY_PRO)) {
        $response = array_merge($response, [
          
          'manages' => $model->manages()->limit(10)->offset(0)->orderBy('properties.created_at')->get($this->propertyBasicFieldList()),
          'user_services' => $model->userServices()->limit(10)->offset(0)->orderBy('created_at')->get($this->userServiceBasicFieldList()),
          'viewings' => $model->viewings()->limit(10)->offset(0)->orderBy('created_at')->get($this->viewingBasicFieldList()),
          //'landlords' => $model->landlords()->limit(10)->offset(0)->orderBy('created_at')->get(),
          'property_pro_offers' => $model->propertyProOffers()->limit(10)->offset(0)->orderBy('property_pros.created_at')->get($this->propertyProOfferBasicFieldList()),
         ]
        );
      }
      if ($model->checkRole(User::ROLE_LANDLORD)) {
        $response = array_merge($response, [
          'my_properties' => $model->myProperties()->limit(10)->offset(0)->orderBy('properties.created_at')->get($this->propertyBasicFieldList()),
          'my_references' => $model->myReferences()->limit(10)->offset(0)->orderBy('references.created_at')->get(),
          'viewings' => $model->viewings()->limit(10)->offset(0)->orderBy('created_at')->get($this->viewingBasicFieldList()),
          //'property_pros' => $model->propertyPros()->limit(10)->offset(0)->orderBy('created_at')->get(),
          'landlord_offers' => $model->landlordOffers()->limit(10)->offset(0)->orderBy('property_pros.created_at')->get($this->propertyProOfferBasicFieldList()),
         ]
        );
      }
      return $response;
  }

}
