<?php

namespace App\Repositories;

use App\Models\Payin;

class PayinRepository extends ParentRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'bank_name',
        'account_number',
        'routing_number',
        'currency',
        'iban',
        'countryCode',
        'sort_code',
        'bic',
        'ip',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'ssn',
        'address',
        'legal_name',
        'tax_id',
        'locality',
        'postal_code',
        'region',
        'entity_type',
        'nationality',
        'personal_id_number',
        'payment_gateway_identity_document',
        'payment_gateway_identity_document_id',
        'billing_address',
        'payin_data',
        'smoor_reference',
        'user_reference',
        'reference',
        'token',
        'verified',
        'payment_gateway_response',
        'verification_response',
        'default',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Payin::class;
    }
    
    
    public function createSubMerchantAccount(Payin $model)
    {
        $request = [
          "country" => $model->countryCode,
          "email" => $model->email,
          "type" => "custom",
          "external_account" => [
            "object" => "bank_account",
            "country" => $model->countryCode,
            "currency" => $model->currency,
            "routing_number" => $model->routing_number
          ],
          "legal_entity" => [
            "address" => [
              "city" => $model->locality,
              "line1" => $model->address,
              "postal_code" => $model->postal_code,
            ],
            "dob" => [
              "day" => $model->date_of_birth->day,
              "month" => $model->date_of_birth->month,
              "year" => $model->date_of_birth->year,
            ],
            "first_name" => $model->first_name,
            "last_name" => $model->last_name,
            "type" => $model->entity_type,
          ],
          "tos_acceptance" => [
            "date" => isset($model->created_at) ? $model->created_at->timestamp: \Carbon\Carbon::now()->timestamp,
          ]
        ];
        if (isset($model->ip)) {
          $request['tos_acceptance']['ip'] = $model->ip;
        } else {
          $request['tos_acceptance']['ip'] = $_SERVER['SERVER_ADDR'];
        }
        if (isset($model->personal_id_number)) $request['legal_entity']['personal_id_number'] = $model->personal_id_number;
        if (isset($model->iban)) $request['external_account']['iban'] = $model->iban;
        if (isset($model->account_number)) $request['external_account']['account_number'] = $model->account_number;
        if (isset($model->sort_code)) $request['external_account']['sort_code'] = $model->sort_code;
        $account = \Stripe\Account::create($request);
        
        if ($account->legal_entity->verification->status == "verified") {
          $model->verified = true;
        }
        $model->verification_response = json_encode($account->verification);
        $model->payment_gateway_response = json_encode($account);
        $model->user_reference = $account->id;
        $model->user->account_reference = $account->id;
        $model->user->save();
    }
    
    public function updateSubMerchantAccount($model)
    {
      $account = \Stripe\Account::retrieve($model->user_reference);
      $account->default_currency = $model->currency;
      
      $account->legal_entity->address->city = $model->locality;
      $account->legal_entity->address->line1 = $model->address;
      $account->legal_entity->address->postal_code = $model->postal_code;
      
      $account->legal_entity->dob->day = $model->date_of_birth->day;
      $account->legal_entity->dob->month = $model->date_of_birth->month;
      $account->legal_entity->dob->year = $model->date_of_birth->year;
      
      $account->legal_entity->first_name = $model->first_name;
      $account->legal_entity->last_name = $model->last_name;
      if ($account->legal_entity->verification->status != "verified") $account->legal_entity->personal_id_number = $model->personal_id_number;
      if ($model->payment_gateway_identity_document_id) {
        if (isset($account->legal_entity->verification)) {
          $account->legal_entity->verification->document = $model->payment_gateway_identity_document_id;
        } else {
          $account->legal_entity->verification = [ 'document' => $model->payment_gateway_identity_document_id ];
        }
      }
      
      $account->save();
      if ($account->legal_entity->verification->status == "verified") {
        $model->verified = true;
      }
      $model->verification_response = json_encode($account->verification);
      $model->payment_gateway_response = json_encode($account);
      $model->save();
      
      return $model;
    }
    
    public function deleteSubMerchantAccount($model)
    {
      if (isset($model->user_reference)) {
        $account = \Stripe\Account::retrieve($model->user_reference);
        $response = $account->delete();
        return $response->deleted;
      }
      return true;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $model->user->id,
        'fromUserId' => config('business.admin.id'),
      ] ))->notify();
  }

}
