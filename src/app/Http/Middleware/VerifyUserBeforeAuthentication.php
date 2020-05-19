<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VerifyUserBeforeAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      $input = $request->all();
      if (isset($input['username'])) {
        $user = User::where('username' , $input['username'])->whereNull('deleted_at')->first();
      } else if (isset($input['email'])) {
        $user = User::where('email' , $input['email'])->whereNull('deleted_at')->first();
      }
      if (!$user) {
        return response(["success" => false, "message" => "no account found"], "404");
      }
      if (!Hash::check($input['password'], $user->password)) {
        return response(["success" => false, "message" => "invalid password"], "400");
      }
      if ($user->verified == 0) {
        return response(["success" => false, "message" => "User Unverified"], "400");
      }
      return $next($request);
    }
}