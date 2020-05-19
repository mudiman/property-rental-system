<?php

namespace App\Http\Requests\API;

use App\Models\Transaction;

class CreateTransactionAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;

      $transaction = $this->all();
      if (isset($transaction['user_id'])) {
        return $this->user()->id == $transaction['user_id'];
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
        return Transaction::rules(0, []);
    }
}
