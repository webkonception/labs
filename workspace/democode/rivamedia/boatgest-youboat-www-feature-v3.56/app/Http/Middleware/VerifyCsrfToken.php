<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;
use Session;
class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->shouldPassThrough($request) ||
            $this->tokensMatch($request)
        ) {
            return $this->addCookieToResponse($request, $next($request));
        }

        $errors = [
            '_token' => [
                'Your session has expired. Please try logging in again.'
            ]
        ];

        /**
         * Generate a new token for more security
         */
        Session::regenerateToken();

        /**
         * Redirect to the last step
         * Refill any old inputs except _token (it would override our new token)
         * Set the error message
         */
        //return Redirect::back()->withInput($request->except('_token'))->withErrors($errors);

        if($request->input('_token')) {
            if ( \Session::getToken() != $request->input('_token')) {
                //return redirect()->guest('/')->with('global', 'Your session has expired. Please try logging in again.');
                return redirect()->back()->withInput($request->except('_token'))->withErrors($errors)->with('message', $errors['_token']);
            }
        }
        return parent::handle($request, $next);

        //throw new TokenMismatchException;
    }
}
