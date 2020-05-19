<?php

namespace App\Http\Requests\API;

use App\Models\Score;
use App\Support\Helper;

class UpdateScoreAPIRequest extends APIBaseRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'title' => 'min:3',
          'category' => 'min:3',
          'weight' => Helper::FLOAT_REGEX,
          'min_percentage' => Helper::FLOAT_REGEX,
          'max_percentage' => Helper::FLOAT_REGEX,
        ] 
          + Score::rules(0, []);
    }
}
