<?php

namespace App\Http\Requests\API;

use App\Models\Service;

class CreateServiceAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $service = $this->all();
      if (isset($service['created_by'])) {
        return $this->user()->id == $service['created_by'];
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
        return Service::$rules;
    }
}
