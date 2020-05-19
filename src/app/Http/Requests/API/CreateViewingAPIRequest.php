<?php

namespace App\Http\Requests\API;

use App\Models\Viewing;
use Carbon\Carbon;

class CreateViewingAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $this->checkAdminVerified();
        
        $viewing = $this->all();
        
        if ((isset($viewing['type']) && $viewing['type'] == Viewing::TYPE_CUSTOM)) return true;
        
        if ((!isset($viewing['type']) || (isset($viewing['type']) && $viewing['type'] == Viewing::TYPE_NORMAL))
            && isset($viewing['conducted_by']) && isset($viewing['property_id'])) {
          return $this->user()->id == $viewing['conducted_by'] 
              && (Property::where('id', $viewing['property_id'])->where('landlord_id', $this->user()->id)->exists() || 
              PropertyPro::where('property_id', $this->property)->where('property_pro_id', $this->user()->id)->exists());
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
        $viewing = $this->all();
        $start_datetime = $this->parseDateTimeOrGiveNowPlusAnHourIfLess($viewing, 'start_datetime');
        
        return [
          'start_datetime' => 'required|date_format:Y-m-d\TH:i:s\Z|after:'.$start_datetime->toIso8601String(),
          'end_datetime' => 'required|date_format:Y-m-d\TH:i:s\Z|after:'.$start_datetime->addMinutes(15)->toIso8601String(),
        ] + Viewing::$rules;
    }
    
    public function messages() 
    {
      return [
        'unique_with' => 'Already made this request',
      ];
    }
}
