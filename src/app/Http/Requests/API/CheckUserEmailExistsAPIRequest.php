<?php

namespace App\Http\Requests\API;

class CheckUserEmailExistsAPIRequest extends APIBaseRequest {

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return parent::authorize() || true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [
      'email' => 'required',
    ];
  }

}
