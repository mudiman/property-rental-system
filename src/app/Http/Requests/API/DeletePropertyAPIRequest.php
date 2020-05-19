<?php

namespace App\Http\Requests\API;

use App\Models\Property;
use App\Models\PropertyPro;

class DeletePropertyAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return Property::where('id', $this->property)->where('landlord_id', $this->user()->id)->exists() || 
              PropertyPro::where('property_id', $this->property)->where('property_pro_id', $this->user()->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
