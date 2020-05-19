<?php

namespace App\Http\Requests\API;

use App\Models\User;

class DeleteUserAPIRequest extends APIBaseRequest
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
        return [];
    }
}
