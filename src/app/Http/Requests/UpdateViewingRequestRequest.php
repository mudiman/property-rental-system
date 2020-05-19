<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ViewingRequest;
use Validator;
use Illuminate\Validation\Rule;

class UpdateViewingRequestRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
        
      $viewingRequest = ViewingRequest::findorfail($this->viewing_request);
      return Viewing::where('id', $viewingRequest->viewings_id)->where('conducted_by', $this->user()->id)->exists() 
          || ViewingRequest::where('id', $this->viewing_request)->where('view_by_user', $this->user()->id)->exists();
    }
    
    public function rules()
    {
        return [
          'viewing_id' => 'exists:viewings,id|unique_with:viewing_requests, view_by_user',
          'view_by_user' => 'exists:users,id',
        ] + ViewingRequest::$rules;
    }
    
}
