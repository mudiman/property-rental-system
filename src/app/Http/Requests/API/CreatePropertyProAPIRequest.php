<?php

namespace App\Http\Requests\API;

use App\Models\PropertyPro;
use App\Models\Payin;

class CreatePropertyProAPIRequest extends APIBaseRequest
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
      if (isset($propertyPro['property_pro_id']) && isset($propertyPro['landlord_id'])) {
        return $this->user()->id == $propertyPro['property_pro_id'] || $this->user()->id == $propertyPro['landlord_id'];
      }
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PropertyPro::$rules;
    }
}
