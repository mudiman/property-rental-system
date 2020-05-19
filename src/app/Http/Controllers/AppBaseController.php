<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Smoor",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
    
    public function sendHtmlError($error, $code = 404)
    {
      $view = 'errors.'.$code;
      if(!view()->exists($view)){
          $view = 'errors.generic';
      }
      return response()
          ->view($view, ['error' => $error, 'code' => $code]);
    }
    
    protected function applyUserFilter($query, $class, $get, $id_field_name) {
      $name = $get;
      $rows = User::where('first_name', 'like', '%' . $name . '%')
          ->orWhere('last_name', 'like', '%' . $name . '%')
          ->get();
      $ids = [];
      foreach ($rows as $row) {
        $ids[] = $row->id;
      }
      if ($query == null) {
        $query = call_user_func(array($class, 'whereIn'), $id_field_name, $ids);
      } else {
        $query->whereIn($id_field_name, $ids);
      }
      return array($query, $name);
    }
    
    protected function removeEmptyFields(&$input) {
      foreach ($input as $key => $value) {
        if (empty($value)) {
          unset($input[$key]);
        }
      }
    }
}
