<?php

namespace App\Http\Requests\API;

use App\Models\UserService;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserServiceAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return UserService::where('id', $this->userService)->where('user_id', $this->user()->id)->exists();
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
