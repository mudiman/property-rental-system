<?php

namespace App\Http\Requests\API;

use App\Models\Payin;
use Illuminate\Support\Facades\Validator;

class CreatePayinAPIRequest extends APIBaseRequest
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
      
      $payin = $this->all();
      if (isset($payin['user_id'])) {
        return $this->user()->id == $payin['user_id'];
      }
    }

    public function validator() {
      $inputData =  $this->request->all()
          + $this->user()->toArray();
      return Validator::make($inputData, 
          Payin::$rules, []);
    }
}
