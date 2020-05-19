<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\Relation;

class UpdateDocumentRequest extends FormRequest
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
        'name' => 'required',
        'type' => 'required',
        'documentable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'documentable_id' => 'required|poly_exists:documentable_type',
      ];
    }
}
