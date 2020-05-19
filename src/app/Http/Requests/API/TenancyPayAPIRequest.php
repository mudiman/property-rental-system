<?php

namespace App\Http\Requests\API;

use App\Models\Tenancy;

class TenancyPayAPIRequest extends APIBaseRequest
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
      
      return Tenancy::where('id', $this->route('id'))->where('tenant_id', $this->user()->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'transaction_id' => 'exists:transactions,id',
        ];
    }
}
