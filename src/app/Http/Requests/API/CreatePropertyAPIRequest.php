<?php

namespace App\Http\Requests\API;

use App\Models\Property;

class CreatePropertyAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $property = $this->all();
        if (isset($property['landlord_id'])) {
          return $this->user()->id == $property['landlord_id'];
        }
        if (isset($property['property_pro_id'])) {
          return $this->user()->id == $property['property_pro_id'];
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
        return Property::$rules;
    }
}
