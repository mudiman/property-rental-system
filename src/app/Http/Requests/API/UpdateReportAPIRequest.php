<?php

namespace App\Http\Requests\API;

use App\Models\Report;

class UpdateReportAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return Report::where('id', $this->report)->where('by_user', $this->user()->id)->exists();
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