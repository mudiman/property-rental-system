<?php

namespace App\Http\Requests\API;

use App\Models\Tenancy;

class CreateTenancyAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;

      $tenancy = $this->all();
      if (isset($tenancy['tenant_id'])) {
        return $this->user()->id == $tenancy['tenant_id'];
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
        return Tenancy::$rules;
    }
}
