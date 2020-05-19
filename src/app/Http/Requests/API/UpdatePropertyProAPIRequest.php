<?php

namespace App\Http\Requests\API;

use App\Models\PropertyPro;
use App\Models\Payin;

class UpdatePropertyProAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $propertyPro = $this->all();
      if (isset($propertyPro['property_pro_payin_id'])) {
        $proExists = Payin::where('user_id', $this->user()->id)->where('id', $propertyPro['property_pro_payin_id'])->exists();
        if(!$proExists) return false;
      }
      return PropertyPro::where('id', $this->propertyPro)->where(function ($query) {
          $query->where('landlord_id', $this->user()->id)
                ->orWhere('property_pro_id', $this->user()->id);
      })->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'landlord_id' => 'exists:users,id',
          'property_pro_id' => 'exists:users,id',
          'property_id' => 'exists:properties,id',
          'service_id' => 'exists:services,id',
          'price_type' => 'exists:service_fee_types,title',
        ] ;
    }
}
