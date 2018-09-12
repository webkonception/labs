<?php namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use App;
use Auth;
use Route;
use DB;

use LaravelLocalization;
use Jenssegers\Agent\Agent;

use App\CustomersCaracts;

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
        // Country Code
        if (session()->has('country_code')) {
            $country_code = session()->get('country_code');
        } else {
            $appFallbackSubdomain = config('app.fallback_subdomain');
            $country_code = $appFallbackSubdomain;
            session()->put('subdomain', $appFallbackSubdomain);
            session()->put('country_code', $country_code);
        }
        $country_code = mb_strtolower($country_code);

        $view->with('country_code', $country_code);
        config(['app.country_code' => $country_code]);

        $viewPath = $view->getPath();
        if (preg_match("/layouts/", $viewPath) || preg_match("/partials/", $viewPath) || preg_match("/vendor/", $viewPath)) {
            //
        } else {
            debug('>>>> GlobalComposer <<<<');

            if(Auth::check() && (!isset($customer_denomination) || empty($customer_denomination))) {
                $customer_denomination = '';
                $ci_email = Auth::user()->email;

                //$ci_username = Auth::user()->username;
                $user_id = Auth::user()->id;

                $customerCaracts = CustomersCaracts::
                select(DB::raw('firstname as ci_firstname, name as ci_last_name, country_id as ci_countries_id, phone_1 as ci_phone'))
                    ->where('user_id', '=', $user_id)
                    ->where('emails', $ci_email)
                    ->get();

                $customerCaracts = json_decode(json_encode($customerCaracts), true);
                if(!empty($result)) {
                    $arrayCustomerCaracts = $customerCaracts[0];

                    if(array_key_exists('ci_last_name', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_last_name"])) {
                        $customer_denomination .= $arrayCustomerCaracts['ci_last_name'];
                    }
                    if(array_key_exists('ci_firstname', $arrayCustomerCaracts) && !empty($arrayCustomerCaracts["ci_firstname"])) {
                        $customer_denomination .= ' ' . $arrayCustomerCaracts['ci_firstname'];
                    }
                }
                $view->with('customer_denomination', $customer_denomination);
            }

            // View Name
            $view_name = $view->getName();
            session()->put('view_name', $view_name);
            $view->with('view_name', $view_name);

            // Is Admin ?
            $isAdmin = false;
            if (Auth::check()) {
                if (Auth::user()->role_id == config('quickadmin.defaultRole')) {
                    $isAdmin = true;
                }
            }
            $view->with('isAdmin', $isAdmin);

            // Current Route
            $currentRoute = Route::currentRouteName();
            $view->with('currentRoute', $currentRoute);

            // Current Route Action
            $currentRouteAction = Route::currentRouteAction();
            $view->with('currentRouteAction', $currentRouteAction);

            // Current Controller & Current Action
            if (isset($currentRouteAction)) {
                $currentController = explode("@", class_basename($currentRouteAction))[0];
                $view->with('currentController', $currentController);

                $currentAction = explode("@", class_basename($currentRouteAction))[1];
                $view->with('currentAction', $currentAction);
            }
            $website_name = config('youboat.' . $country_code . '.website_name');
            $view->with('website_name', $website_name);
            $website_phone = config('youboat.' . $country_code . '.phone');
            $view->with('website_phone', $website_phone);
        }
        config(['app.url' => config('youboat.' . $country_code . '.website_url')]);
        \URL::forceRootUrl(\Config::get('app.url'));

        // Current locale
        $currentLocale = LaravelLocalization::getCurrentLocale();
        $view->with('currentLocale', $currentLocale);
        app()->setLocale($currentLocale);
        app('laravellocalization')->setLocale($currentLocale);

        $agent = new Agent();
        $view->with('agent', $agent);

        $ad_banners = config('youboat.' . $country_code . '.ad_banners');
        $view->with('ad_banners', $ad_banners);

    }
}
