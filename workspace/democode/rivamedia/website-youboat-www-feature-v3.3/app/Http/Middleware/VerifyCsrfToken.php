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
        '/',
        'homepage',
        'for_sale',
        'boat_on_demand',
        'dashboard',
        'dashboard-edit-bod',
        'dashboard-edit-customer',
        //'show_ad_detail',
        'manufacturers',
        'manufacturers_engines',

        'showAdsByManufacturer',
        'showAdsByModel',
        'showAdsByManufacturerEngine',
        'showAdsByModelEngine',
        'showAdsByType',
        'showAdsByCategory',
        'showAdsBySubcategory',

        // Ajax routes
        'ajax_email',

        'ajax_country_contracts',

        'ajax_adstype',
        'ajax_adstypes',
        'ajax_adstype_detail',

        'ajax_category',
        'ajax_categories',

        'ajax_subcategory',
        'ajax_subcategories',

        'ajax_manufacturer',
        'ajax_manufacturers',

        'ajax_model',
        'ajax_models',

        'ajax_manufacturer_engine',
        'ajax_manufacturers_engines',

        'ajax_gateway_manufacturer_engine',
        'ajax_gateway_manufacturers_engines',

        'ajax_model_engine',
        'ajax_models_engines',

        'ajax_country',
        'ajax_countries',

        'ajax_gateway_categories',
        'ajax_gateway_subcategories',

        'ajax_gateway_manufacturer',
        'ajax_gateway_manufacturers',

        'ajax_gateway_model',
        'ajax_gateway_models'

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
