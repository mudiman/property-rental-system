<?php

namespace App\Http\Requests\API;

use App\Models\Like;
use App\Models\Property;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateLikeAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $like = $this->all();
      if (isset($like['likeable_type']) && isset($like['likeable_id']) && isset($like['user_id'])) {
        if ($like['likeable_type'] == Property::morphClass
            && Property::where('id', $like['likeable_id'])->where('landlord_id', $like['user_id'])->exists()) {
          throw new HttpException(403, "You can't like your own property");
        }
        if ($like['likeable_type'] == User::morphClass
            && $like['user_id'] == $like['likeable_id']) {
          throw new HttpException(403, "You can't like yourself");
        }
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
        return Like::rules(0, []);
    }
}
