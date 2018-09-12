<?php

namespace App\Http\Middleware;

use Closure;
use LaravelLocalization;
//use Illuminate\Http\RedirectResponse;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // Do stuff

        //$response = $response instanceof RedirectResponse ? $response : response($response);

        $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate'); //private,
        $response->header('Pragma','no-cache');
        $response->header('Expires','0'); //'Expires','Fri, 01 Jan 1990 00:00:00 GMT'

        $content = $response->content();
        $contentLength = strlen($content);
        $response->header('Content-Length', $contentLength);

        return $response;
    }
}