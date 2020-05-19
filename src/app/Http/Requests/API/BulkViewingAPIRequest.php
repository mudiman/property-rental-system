<?php

namespace App\Http\Requests\API;

use App\Models\Viewing;
use Carbon\Carbon;

class BulkViewingAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $viewing = $this->all();
        
//        if ((isset($viewing['type']) && $viewing['type'] == Viewing::TYPE_CUSTOM)) return true;
//        
//        if ((!isset($viewing['type']) || (isset($viewing['type']) && $viewing['type'] == Viewing::TYPE_NORMAL))
//            && isset($viewing['conducted_by']) && isset($viewing['property_id'])) {
//          return $this->user()->id == $viewing['conducted_by'] 
//              && (Property::where('id', $viewing['property_id'])->where('landlord_id', $this->user()->id)->exists() || 
//              PropertyPro::where('property_id', $this->property)->where('property_pro_id', $this->user()->id)->exists());
//        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          '*.start_datetime' => 'required|date_format:Y-m-d\TH:i:s\Z|after:'.Carbon::now()->addHours(1)->toIso8601String(),
          '*.end_datetime' => 'required|date_format:Y-m-d\TH:i:s\Z|after:'.Carbon::now()->addHours(1)->addMinutes(15)->toIso8601String(),
          //'*.property_id' => 'required|exists:properties,id|unique_with:viewings, start_datetime,deleted_at,NULL',
          '*.conducted_by' => 'required|exists:users,id',
          '*.status' => "in:".Viewing::AVAILABLE
            .",".Viewing::CONFIRM
            .",".Viewing::NOSHOW_TENANT
            .",".Viewing::NOSHOW_LANDLORD
            .",".Viewing::DONE
            .",".Viewing::CANCEL,
          '*.type' => "in:".Viewing::TYPE_NORMAL
            .",".Viewing::TYPE_CUSTOM,
      ];
    }
}
