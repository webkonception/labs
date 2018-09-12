<?php

namespace App\Http\Middleware;

use Closure;
//use Session;

class BeforeMiddleware
{
    /**
     * The availables languages.
     *
     * @array $languages
     */
    //protected $languages;
    //protected $subdomain_languages;

    public function handle($request, Closure $next)
    {
        ///debug('>> BeforeMiddleware <<');
        ///debug($request->all());

        //session()->forget('subdomain');
        //session()->forget('country_code');
        //session()->forget('locale');
        //session()->forget('app.languages');

//        debug('session()->get(\'locale\')');
//        debug(session()->get('locale'));
//        debug('app(\'laravellocalization\')->getCurrentLocale()');
//        debug(app('laravellocalization')->getCurrentLocale());
//        debug('app()->getLocale()');
//        debug(app()->getLocale());


        return $next($request);
    }
}