<?php
// app/Http/Middleware/SubDomainLanguage.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use LaravelLocalization;
use Route;

class SubDomainLanguage
{
    protected $configAppLanguages;
    protected $subdomain;
    protected $subdomain_languages;

    public function handle($request, Closure $next)
    {
        ///debug('>> SubDomainLanguage <<');
        session()->forget('subdomain');
        //session()->forget('country_code');
        //session()->forget('locale');
        //session()->forget('app.languages');

        $www = $domain = $subdomain = $tld = '';
        // Variable subdomains
        if (!session()->has('subdomain')) {
            if (preg_match("/www[.]youboat[.]([a-z0-9]+[.])*/",$_SERVER["SERVER_NAME"])) {
                list($www,$domain,$subdomain) = explode(".", $_SERVER["SERVER_NAME"]);
            } elseif (preg_match("/([a-z0-9]+[.])*youboat[.](local|com)/",$_SERVER["SERVER_NAME"])) {
                list($subdomain,$domain,$tld) = explode(".", $_SERVER["SERVER_NAME"]);
            } elseif (NULL != Route::current() && Route::current()->hasParameter('subdomain')) {
                $current_params = Route::current()->parameters();
                $subdomain = $current_params['subdomain'];
            } else {
                $subdomain = config('app.fallback_subdomain');
            }
            $subdomain = ($subdomain == 'dev') ? config('app.fallback_subdomain') : $subdomain;
            session()->put('subdomain', $subdomain);
        } else if (session()->has('subdomain')) {
            $subdomain = session()->get('subdomain');
        } else {
            $subdomain = config('app.fallback_subdomain');
            session()->put('subdomain', $subdomain);
        }
        //$subdomain = 'es'; // for testing
        //$subdomain = 'nl'; // for testing
        //$subdomain = 'be'; // for testing
        $subdomain = ($subdomain == 'dev') ? config('app.fallback_subdomain') : $subdomain;

        //session()->put('subdomain', $subdomain);
        config(['request.subdomain' => $subdomain]);

        $current_locale = mb_strtolower(config('youboat.' . $subdomain . '.locale'));
        $country_code = str_replace('GB', 'UK', config('youboat.' . $subdomain . '.country_code'));

        config(['app.locale' => $current_locale]);
        config(['app.fallback_subdomain' => $subdomain]);

        session()->put('country_code', $country_code);
        config(['app.country_code' => $country_code]);

        config(['app.locale' => $subdomain]);
        //app('laravellocalization')->setLocale($current_locale);

        $configAppLanguages = config('app.languages');
        //$subdomain = config('request.subdomain');
        $subdomain_languages = config('app.subdomain_languages');

        // Get Languages used for Subdomain
        if (array_key_exists($subdomain, $subdomain_languages)) {
            $configAppLanguages = $subdomain_languages[$subdomain];
            config(['app.languages' => $configAppLanguages]);
            session()->put('app.languages', $configAppLanguages);
        }

        if(count($configAppLanguages) > 1) {
            app('config')->set('laravellocalization.hideDefaultLocaleInURL', false);
        } else {
            app('config')->set('laravellocalization.hideDefaultLocaleInURL', true);
        }

        // Set Locale Language
        if (session()->has('locale') && in_array(session()->get('locale'), $configAppLanguages)) {
            //debug('// Set Locale Language');
            //debug(session()->get('locale'));
            app()->setLocale(session()->get('locale'));
            //app('laravellocalization')->setLocale(session()->get('locale'));
        } else if (null !== app('laravellocalization')->getCurrentLocale()) {
            //debug('##laravellocalization Set Locale Language');
            app()->setLocale(app('laravellocalization')->getCurrentLocale());
            session()->put('locale', app('laravellocalization')->getCurrentLocale());
        } else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            app()->setLocale(config('app.fallback_locale'));
            session()->put('locale', config('app.fallback_locale'));
        }
        return $next($request);
    }
}
