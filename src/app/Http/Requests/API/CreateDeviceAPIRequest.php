<?php

namespace App\Http\Requests\API;

use App\Models\Device;

class CreateDeviceAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $device = $this->all();
      if (isset($device['user_id'])) {
        return $this->user()->id == $device['user_id'];
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
      return Device::rules(0, [
        'user_id' => 'required|exists:users,id',
        'device_id' => 'required|unique:devices',
        'api_version' => 'required',
      ]);
    }
}
