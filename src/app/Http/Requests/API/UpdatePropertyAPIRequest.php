<?php

namespace App\Http\Requests\API;

use App\Models\Property;
use App\Models\PropertyPro;
use App\Support\Helper;

class UpdatePropertyAPIRequest extends APIBaseRequest {

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    if ($this->user()->isAdmin())
      return true;

    $property = $this->all();

    if (isset($property['status']) && $property['status'] == Property::STATUS_LIVE && !$this->checkAdminVerified())
      return false;

    return Property::where('id', $this->property)->where('landlord_id', $this->user()->id)->exists() ||
        PropertyPro::where('property_id', $this->property)->where('property_pro_id', $this->user()->id)->exists();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    
    $property = $this->all();
    $rules = [
      'available_date' => 'date_format:Y-m-d|after:today',
      'landlord_id' => 'exists:users,id',
      'property_pro_id' => 'exists:users,id',
      'cordinate' => 'regex:/^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$/',
      'property_type' => 'exists:property_room_types,title',
      'letting_type' => 'exists:letting_types,title',
      'bathroom_type' => 'exists:bathroom_types,title',
      
      'rent_per_month' => 'numeric|'.Helper::FLOAT_REGEX,
      'rent_per_week' => 'numeric|'.Helper::FLOAT_REGEX,
      'rent_per_night' => 'numeric|'.Helper::FLOAT_REGEX,
      'minimum_accepted_price' => 'numeric|'.Helper::FLOAT_REGEX,
      'security_deposit_weeks' => 'numeric',
      'security_deposit_amount' => 'numeric|'.Helper::FLOAT_REGEX,
      'security_deposit_holding_amount' => 'numeric|'.Helper::FLOAT_REGEX,
      'minimum_accepted_price_short_term_price' => 'numeric|'.Helper::FLOAT_REGEX,
      'contract_length_months' => 'numeric|min:1',
      'shortterm_rent_per_month' => 'numeric|'.Helper::FLOAT_REGEX,
      'shortterm_rent_per_week' => 'numeric|'.Helper::FLOAT_REGEX,
    ];
    
    if (isset($property['rent_per_month']) && isset($property['minimum_accepted_price'])) {
      $rules['rent_per_month'] .= '|min:'.$property['minimum_accepted_price'];
    }
    return $rules;
  }

}
