<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\Relation;

class UpdateImageRequest extends FormRequest
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
        'imageable_id' => 'poly_exists:imageable_type',
        'imageable_type' => 'in:'.implode(",", array_keys(Relation::morphMap())),
        'type' => 'required',
      ];
    }
}
