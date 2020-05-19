<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return User::where('id', $this->user)->where('id', $this->user()->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return User::rules($this->route('user'), [
            'email' => "email|unique:users,email,".$this->route('user').",id,deleted_at,NULL",
            'password' => ['min:6', 'regex:'.User::PASSWORD_REGEX_RULE],
            'available_to_move_on' => 'date_format:Y-m-d',
          ]);
    }
}
