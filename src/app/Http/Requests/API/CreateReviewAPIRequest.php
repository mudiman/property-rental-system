<?php

namespace App\Http\Requests\API;

use App\Models\Review;

class CreateReviewAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $review = $this->all();
      if (isset($review['by_user'])) {
        return $this->user()->id == $review['by_user'];
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
        return Review::rules(0, []);
    }
}
