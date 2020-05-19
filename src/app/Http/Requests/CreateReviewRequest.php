<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Review;

class CreateReviewRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $review = $this->all();
      if (isset($review['by_user']) 
          && isset($review['for_user'])
          && $review['by_user'] == $review['for_user']) {
        throw new HttpException(403, "You can't review yourself");
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
