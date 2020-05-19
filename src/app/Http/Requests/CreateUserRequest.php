<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class CreateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return User::rules(0, [
            'first_name' => 'required',
            'email' => "required|email|unique:users,email,NULL,id,deleted_at,NULL",
            'password' => ['required', 'min:6', 'regex:'.User::PASSWORD_REGEX_RULE]
          ]);
    }
}
