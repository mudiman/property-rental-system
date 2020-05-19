<?php

namespace App\Http\Requests\API;

use App\Models\Feedback;

class CreateFeedbackAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $feedback = $this->all();
      if (isset($feedback['user_id'])) {
        return $this->user()->id == $feedback['user_id'];
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
        return Feedback::$rules;
    }
}
