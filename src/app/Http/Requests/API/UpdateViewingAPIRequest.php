<?php

namespace App\Http\Requests\API;

use App\Models\Viewing;
use InfyOm\Generator\Request\APIRequest;
use Carbon\Carbon;

class UpdateViewingAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return Viewing::where('id', $this->viewing)->where(function ($query) {
            $query->where('conducted_by', $this->user()->id)
                  ->orWhere('created_by', $this->user()->id);
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
        'property_id' => 'exists:properties,id',
        'start_datetime' => 'date_format:Y-m-d\TH:i:s\Z',
        'end_datetime' => 'date_format:Y-m-d\TH:i:s\Z',
        'conducted_by' => 'exists:users,id',
      ] + Viewing::$rules;
    }
}
