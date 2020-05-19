<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use App\Models\User;
use App\Presenters\UserPresenter;
use App\Repositories\UserRepository;

class AuthenticationAttachUserData
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
      $response = $next($request);
      $content = json_decode($response->getContent(), true);
      if (isset($content['error'])) {
        return $response;
      }
      $input = $request->all();
      $userRepository = \App::make(UserRepository::class);
      $userRepository->setPresenter(UserPresenter::class);
      $user = $userRepository->findByField('username',$input['username']);
      $content['user'] = $user['data'][0];
      $response->setContent(json_encode($content));

      return $response;
    }
}