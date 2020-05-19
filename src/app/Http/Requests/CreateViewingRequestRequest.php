<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ViewingRequest;
use Validator;
use Illuminate\Validation\Rule;

class CreateViewingRequestRequest extends FormRequest
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

    public function validator(){
      return Validator::make($this->request->all(), [
          'view_by_user' => 'required|exists:users,id',
          'viewing_id' => [
              'required',
              'exists:viewings,id',
              Rule::unique('viewing_requests')->where(function ($query) {
                $data = $this->request->all();
                if (!isset($data['view_by_user'])) $data['view_by_user'] = null;
                $query->where('view_by_user', $data['view_by_user'])->where('deleted_at', null);
            }),
          ],
          'status' => "in:".ViewingRequest::STATUS_REQUEST.",".ViewingRequest::STATUS_CONFIRM,
      ],
      [
        'viewing_id.required' => 'Viewing_id is required',
        'viewing_id.unique' => 'You have already requested on this viewing',
      ]);
    }
}
