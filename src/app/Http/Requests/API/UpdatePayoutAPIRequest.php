<?php

namespace App\Http\Requests\API;

use App\Models\Payout;

class UpdatePayoutAPIRequest extends APIBaseRequest
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
        
        return Payout::where('id', $this->payout)->where('user_id', $this->user()->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'token' => "required|unique:payouts,token"
        ];
    }
}
