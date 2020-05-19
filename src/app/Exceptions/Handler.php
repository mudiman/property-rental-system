<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Acacha\Stateful\Exceptions\IllegalStateTransitionException;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception) 
            && config('app.env') != 'local'
            && !$exception instanceof IllegalStateTransitionException
            && !$exception instanceof OAuthServerException) {
          app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
      if ($request->wantsJson() 
          && (config('app.env') != 'local'
          || config('app.debug') == false))
      {
          // Define the response
          $response = [
              'success' => false,
              'message' => $exception->getMessage()
          ];
          // If the app is in debug mode
          if (config('app.debug'))
          {
              // Add the exception class name, message and stack trace to response
              $response['exception'] = get_class($exception); // Reflection might be better here
              $response['message'] = $exception->getMessage();
              $response['trace'] = $exception->getTrace();
          }

          // Default response of 400
          $status = 400;
          // If this exception is an instance of HttpException
          if ($this->isHttpException($exception))
          {
              // Grab the HTTP status code from the Exception
              $status = $exception->getStatusCode();
          }
          if ($exception instanceof MethodNotAllowedHttpException)
          {
              // Grab the HTTP status code from the Exception
              $response['message'] = 'Method Not Allowed';
          }
          if ($exception instanceof ValidationException) {
              return $this->convertValidationExceptionToResponse($exception, $request);
          }
          if ($exception instanceof IllegalStateTransitionException)
          {
            // Grab the HTTP status code from the Exception
            if (!empty($exception->messages()->get('error'))) {
              $response['message'] = $exception->messages()->get('error')[0];
            } elseif (!empty($exception->messages()->getMessages())) {
              $response['message'] = "opps that wasnt suppose to happen, please try again in a moment";
               //$response['message'] = implode("," ,array_values($exception->messages()->getMessages())[0]);
            }
          }
          // Return a JSON response with the response array and status code
          return response()->json($response, $status);
      }
      if ($exception instanceof IllegalStateTransitionException)
      {
        // Grab the HTTP status code from the Exception
        if (!empty($exception->messages()->get('error'))) {
          return response()->view('errors.500', ['error' => $exception->messages()->get('error')[0]], 500);
        } elseif (!empty($exception->messages()->getMessages())) {
           return response()->json(['success' => false, 'error' => implode("," ,array_values($exception->messages()->getMessages())[0])], 400);
        }
        return response()->view('errors.500', ['error' => $exception->messages()->get('error')[0]], 500);
      }
      return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
