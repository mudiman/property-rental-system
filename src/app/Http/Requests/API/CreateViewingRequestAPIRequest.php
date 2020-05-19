<?php

namespace App\Http\Requests\API;

use App\Models\ViewingRequest;
use App\Models\Viewing;
use Validator;
use Illuminate\Validation\Rule;

class CreateViewingRequestAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $this->checkAdminVerified();
        
        return true;
    }

    
    public function validator(){
      $data = $this->request->all();
      if (!isset($data['viewing_id'])) $data['viewing_id'] = null;
      
      return Validator::make($this->request->all(), [
          'view_by_user' => 'required|exists:users,id,deleted_at,NULL',
          'viewing_id' => [
              'required',
              Rule::unique('viewing_requests')->where(function ($query) {
                $data = $this->request->all();
                if (!isset($data['view_by_user'])) $data['view_by_user'] = null;
                $query->where('view_by_user', $data['view_by_user'])->where('deleted_at', null);
            }),
            Rule::exists('viewings', 'id')                     
              ->where(function ($query) {                      
                  $query->where('status', Viewing::AVAILABLE)->where('deleted_at', null);                
              }), 
          ],
          'status' => "in:".ViewingRequest::STATUS_REQUEST.",".ViewingRequest::STATUS_CONFIRM,
      ],
      [
        'viewing_id.required' => 'Viewing is required',
        'viewing_id.exists' => 'Viewing is not available',
        'viewing_id.unique' => 'A request for given property and time already exists',
      ]);
    }
}
