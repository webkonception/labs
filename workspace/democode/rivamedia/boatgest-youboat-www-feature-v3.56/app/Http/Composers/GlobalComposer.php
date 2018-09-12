<?php namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use App;
use Auth;
use Route;
use LaravelLocalization;

//use Session;
//use Config;
class GlobalComposer
{
    /**
    * @var Guard
    */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
    * Bind data to the view.
    *
    * @param  View $view
    * @return void
    */
    public function compose(View $view)
    {
        $viewPath = $view->getPath();
        // Current Route
        $currentRoute = Route::currentRouteName();
        $view->with('currentRoute', $currentRoute);

        // Current Route Action
        $currentRouteAction = Route::currentRouteAction();
        $view->with('currentRouteAction', $currentRouteAction);

        // Is Admin ?
        $isAdmin = false;
        if (Auth::check()) {
            if (Auth::user()->role_id == config('quickadmin.defaultRole')) {
                $isAdmin = true;
            }
        }
        $view->with('isAdmin', $isAdmin);

        // Current Controller & Current Action
        if (isset($currentRouteAction)) {
            $currentController = explode("@", class_basename($currentRouteAction))[0];
            $view->with('currentController', $currentController);

            $currentAction = explode("@", class_basename($currentRouteAction))[1];
            $view->with('currentAction', $currentAction);
        }
        if (
            preg_match("/layouts/", $viewPath) ||
            preg_match("/partials/", $viewPath) ||
            preg_match("/vendor/", $viewPath)) {
            //
        } else {
            debug('>>>> GlobalComposer <<<<');

            // Country Code
            if (session()->has('country_code')) {
                $country_code = session()->get('country_code');
            } else {
                $appFallbackSubdomain = config('app.fallback_subdomain');
                $appFallbackCountryCode = config('app.fallback_country_code');
                //$country_code = $appFallbackSubdomain;
                $country_code = $appFallbackCountryCode;
                session()->put('subdomain', $appFallbackSubdomain);
                session()->put('country_code', $country_code);
            }
            $view->with('country_code', $country_code);

            // View Name
            $view_name = $view->getName();
            session()->put('view_name', $view_name);
            $view->with('view_name', $view_name);
        }

        // Current locale
        $currentLocale = LaravelLocalization::getCurrentLocale();
        $view->with('currentLocale', $currentLocale);
        app()->setLocale($currentLocale);
        app('laravellocalization')->setLocale($currentLocale);
    }
}