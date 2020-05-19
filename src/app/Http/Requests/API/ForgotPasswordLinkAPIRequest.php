<?php

namespace App\Http\Requests\API;


class ForgotPasswordLinkAPIRequest extends APIBaseRequest
{

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [
      'code' => 'required|exists:users,forgot_password_verification_code',
      'user' => 'required|exists:users,id'
    ];
  }

  /**
   * Add parameters to be validated
   * 
   * @return array
   */
  public function all() {
    return array_replace_recursive(
        parent::all(), $this->route()->parameters()
    );
  }

  public function response(array $errors) {
    abort('400', $this->convertErrorToString($errors));
  }

}
