<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Device;

class UpdateDeviceRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return Device::rules(0, [
        'user_id' => 'required|exists:users,id',
        'device_id' => "required|unique:devices,".$this->route('device').",id,deleted_at,NULL",
        'api_version' => 'required',
      ]);
    }
}
