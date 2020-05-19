<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Property;

class UpdatePropertyRequest extends FormRequest
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
        
        if(!$this->user()->isVerifiedByAdmin() 
            && isset($property['status']) && $property['status'] == Property::STATUS_LIVE) return false;
        
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
        return [
          'available_date' => 'date_format:Y-m-d',
          'landlord_id' => 'exists:users,id',

      ] + Property::$rules;
    }
}
