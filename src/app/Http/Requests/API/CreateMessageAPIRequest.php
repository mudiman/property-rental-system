<?php

namespace App\Http\Requests\API;

use App\Models\Message;

class CreateMessageAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
      
        $message = $this->all();
        if (isset($message['by_user'])) {
          return $this->user()->id == $message['by_user'];
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
        return Message::rules(0, []);
    }
}
