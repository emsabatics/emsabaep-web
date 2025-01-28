<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
        /*$this->renderable(function (InvalidOrderException $e, $request) {
            return response()->view('Errores.404', [], 404);
        });*/
    }

    function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                //return response()->view('Errores.404', [], 404);
                //if (Auth::check()) {
                if (session()->has('usuario')) { 
                    return response()->view('Errores.404', [], 404);
                } else {
                    return response()->view('Errores.404_user', [], 404);
                }
            }
        }
        return parent::render($request, $exception);
    }
}
