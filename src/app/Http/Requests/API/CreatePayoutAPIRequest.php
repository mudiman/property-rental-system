<?php

namespace App\Http\Requests\API;

use App\Models\Payout;

class CreatePayoutAPIRequest extends APIBaseRequest
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
      
      $payout = $this->all();
      if (isset($payout['user_id'])) {
        return $this->user()->id == $payout['user_id'];
      }
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Payout::$rules;
    }
    
}
