<?php

namespace App\Http\Requests\API;

use App\Models\ViewingRequest;
use App\Models\Viewing;
use InfyOm\Generator\Request\APIRequest;
use Validator;
use Illuminate\Validation\Rule;

class UpdateViewingRequestAPIRequest extends APIRequest
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
        return Viewing::where('id', $viewingRequest->viewing_id)->where('conducted_by', $this->user()->id)->exists() 
            || ViewingRequest::where('id', $this->viewing_request)->where('view_by_user', $this->user()->id)->exists();
    }

    public function rules()
    {
        return [
          'viewing_id' => 'exists:viewings,id',
          'view_by_user' => 'exists:users,id',
          'status' => "in:".ViewingRequest::STATUS_REQUEST
          .",".ViewingRequest::STATUS_REARRANGE
          .",".ViewingRequest::STATUS_CANCEL
          .",".ViewingRequest::STATUS_CONFIRM,
        ] + ViewingRequest::$rules;
    }
}
