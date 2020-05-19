<?php

namespace App\Http\Requests\API;

use App\Models\Offer;

class OfferSecurityDepositAPIRequest extends APIBaseRequest
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
      
      return Offer::where('id', $this->route('id'))->where('tenant_id', $this->user()->id)->exists();
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
