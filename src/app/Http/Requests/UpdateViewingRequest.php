<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Viewing;

class UpdateViewingRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return Viewing::where('id', $this->viewing)->where(function ($query) {
            $query->where('conducted_by', $this->user()->id)
                  ->orWhere('created_by', $this->user()->id);
        })->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Viewing::$rules;
    }
}
