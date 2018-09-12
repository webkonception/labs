<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    /*protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];*/

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($e instanceof TokenMismatchException){
            $errors='Fatal Error: If you try this procedure again in any page of the site will be permanently banned';
            return Redirect::back()->withInput()->withErrors($errors);
            //return redirect('/');
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if($this->isHttpException($e)){
            if (view()->exists('errors.'.$e->getStatusCode()))
            {
                //var_dump($e);
                return response()->view('errors.'.$e->getStatusCode(), [], $e->getStatusCode());
            }else{
                return response()->view('errors.errors', [], $e->getStatusCode());
            }
        }
        return parent::render($request, $e);
    }
}
