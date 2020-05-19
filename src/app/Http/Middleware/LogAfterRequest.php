<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Middleware;

use Closure;  
use Illuminate\Support\Facades\Log;

/**
 * Description of LogAfterRequest
 *
 * @author muda
 */
class LogAfterRequest {

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
      if ($request->method() == "POST" && strpos($request->url(), "documents")) {
        return;
      }
      Log::debug('app.requests', ['url' => $request->url(),'request' => $request->all(), 'request method' => $request->method(), 'response' => $response]);
    }

}
