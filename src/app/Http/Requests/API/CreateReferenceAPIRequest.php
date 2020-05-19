<?php

namespace App\Http\Requests\API;

use App\Models\Reference;

class CreateReferenceAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $reference = $this->all();
      if (isset($reference['by_user'])) {
        return $this->user()->id == $reference['by_user'];
      }
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Reference::$rules;
    }
}
