<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Carbon\Carbon;

/**
 * Description of APIBaseRequest
 *
 * @author muda
 */
class APIBaseRequest extends APIRequest {

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    if ($this->user()->isAdmin())
      return true;
  }

  public function checkAdminVerified() {
    if (!$this->user()->isVerifiedByAdmin()) {
      throw new HttpException(403, 'You will be able to perform this action, once we have verified your profile');
    } else {
      return true;
    }
  }
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [];
  }

  public function sendError($error, $code = 404) {
    return Response::json(ResponseUtil::makeError($error), $code);
  }

  public function sendHtmlError($error, $code = 404) {
    $view = 'errors.' . $code;
    if (!view()->exists($view)) {
      $view = 'errors.generic';
    }
    return response()
            ->view($view, ['error' => $error, 'code' => $code]);
  }

  public function convertErrorToString($errors) {
    $err = '';
    foreach ($errors as $error) {
      $err .= implode(",", $error);
    }
    return $err;
  }
  
  protected function parseDateTimeOrGiveNowPlusAnHourIfLess($data, $field) {
    $return_datetime = Carbon::now();
    try {
      if (isset($data[$field])) {
       $datetime = Carbon::parse($data[$field]); 
       //dd($return_datetime,$datetime, $return_datetime->diffInHours($datetime));
       if ($return_datetime > $datetime) {
          $return_datetime = $return_datetime->addHours(1);
        }
      }
    } catch (Exception $e) {
      
    }
    return $return_datetime;
  }
  
  protected function parseDateTimeOrGiveNowPlusADayIfLess($data, $field) {
    $return_datetime = Carbon::now();
    try {
      if (isset($data[$field])) {
       $datetime = Carbon::parse($data[$field]); 
       if ($return_datetime > $datetime) {
          $return_datetime = $return_datetime->addDays(1);
        }
      }
    } catch (Exception $e) {
      
    }
    return $return_datetime;
  }

}
