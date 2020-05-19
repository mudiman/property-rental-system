<?php

namespace App\Http\Requests\API;

use App\Models\Image;
use App\Models\Property;
use App\Models\UserService;

class UpdateImageAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $image = Image::findorfail($this->image);
        if ($image->imageable_type == 'property') {
          return Property::where('id', $image->imageable_id)->where('landlord_id', $this->user()->id)->exists() || 
                PropertyPro::where('property_id', $image->imageable_id)->where('property_pro_id', $this->user()->id)->exists();
        }
        if ($image->imageable_type == 'user_service') {
          return UserService::where('id', $image->imageable_id)->where('user_id', $this->user()->id)->exists();
        }
        if ($image->imageable_type == 'user') {
          return $this->user()->id == $image->imageable_id;
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
        return [];
    }
}
