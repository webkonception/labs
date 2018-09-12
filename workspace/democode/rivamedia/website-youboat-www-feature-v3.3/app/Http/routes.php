<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/make_pwd/{password_cleared}', function($password_cleared) {
    return bcrypt($password_cleared);
});

if (App::isLocal()) {
    //**************************************************************//
    // MIDDLEWARE : web, localeSessionRedirect, localizationRedirect//
    //**************************************************************//
    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect']
        ,'middleware' => [ 'localize' ] // Route translate middleware
    ], function() {

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('/robots.txt', function()
        {
            return redirect('/robots/robots_dev.youboat.com.txt');
        });

        Route::get('/sitemap.xml', function($subdomain, $domain)
        {
            return redirect('/sitemaps/sitemap_dev.youboat.com.xml');
        });

        Route::get('/hypes.txt', function($subdomain, $domain)
        {
            return redirect('/hypes/hypes__dev.youboat.com.txt');
        });

        //=========================//
        // Errors routes...
        Route::get('403', function()
        {
            abort(403);
        });
        Route::get('404', function()
        {
            abort(404);
        });
        Route::get('500', function()
        {
            abort(500);
        });
        Route::get('503', function()
        {
            abort(503);
        });

        Route::get(LaravelLocalization::transRoute('routes.ad_not_found'), ['as' => 'ad_not_found', 'uses' => 'AdNotFoundController@index']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('/dealers/{country_code}/{rewrite_url}', ['as' => 'dealer_details', 'uses' => 'DealersController@index']);

        //=========================//
        // Landing routes...
        //Route::get('/', ['as' => 'landing', 'uses' => 'LandingController@index']);
        Route::get(LaravelLocalization::transRoute('routes.landing'), ['as' => 'landing', 'uses' => 'LandingController@index']);
        //Route::post('/', ['as' => 'landing', 'uses' => 'LandingController@store']);
        Route::post(LaravelLocalization::transRoute('routes.landing'), ['as' => 'landing', 'uses' => 'LandingController@store']);

        //=========================//
        // Landing Contact routes...
        Route::get(LaravelLocalization::transRoute('routes.landingcontact'), ['as' => 'landingcontact', 'uses' => 'LandingContactController@create']);
        Route::post(LaravelLocalization::transRoute('routes.landingcontact'), ['as' => 'landingcontact', 'uses' => 'LandingContactController@store']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        // Homepage route...
        //Route::get(LaravelLocalization::transRoute('routes.homepage'), ['as' => 'homepage', 'uses' => 'HomeController@index']);
        Route::get('/', ['as' => 'homepage', 'uses' => 'HomeController@index']);

        //=========================//
        // Contact routes...
        Route::get(LaravelLocalization::transRoute('routes.contact'), ['as' => 'contact', 'uses' => 'ContactController@create']);
        Route::post(LaravelLocalization::transRoute('routes.contact'), ['as' => 'contact', 'uses' => 'ContactController@store']);

        //=========================//
        // News routes...
        //Route::get(LaravelLocalization::transRoute('routes.news_detail'), ['as' => 'news_detail', 'uses' => 'NewsController@index']);
        Route::get(LaravelLocalization::transRoute('routes.news_detail'), function() {
            return redirect(LaravelLocalization::transRoute('routes.home'), 301);
        });
        Route::get(LaravelLocalization::transRoute('routes.news_detail') . '/{country_code}/{date}/{title_slug}', ['as' => 'news_detail', 'uses' => 'NewsController@showNewsDetail']);
        Route::get(LaravelLocalization::transRoute('routes.news_create'), ['as' => 'news_create', 'uses' => 'NewsController@create']);
        Route::post(LaravelLocalization::transRoute('routes.news_create'), ['as' => 'news_create', 'uses' => 'NewsController@store']);

        //=========================//
        // Newsletter routes...
        Route::get(LaravelLocalization::transRoute('routes.newsletter'), ['as' => 'newsletter', 'uses' => 'NewsletterController@create']);
        Route::get(LaravelLocalization::transRoute('routes.newsletter') . '/{email}', function($email) {
            return View::make('newsletter',['email'=>$email]);
        });
        Route::post(LaravelLocalization::transRoute('routes.newsletter'), ['as' => 'newsletter', 'uses' => 'NewsletterController@store']);

        //=========================//
        // Welcome routes...
        Route::get(LaravelLocalization::transRoute('routes.welcome'), ['as' => 'welcome', 'uses' => 'PagesController@index']);

        //=========================//
        // About routes...
        Route::get(LaravelLocalization::transRoute('routes.about'), ['as' => 'about', 'uses' => 'PagesController@index']);

        //=========================//
        // CGV routes...
        Route::get(LaravelLocalization::transRoute('routes.cgv'), ['as' => 'cgv', 'uses' => 'PagesController@index']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        //// boat_on_demand routes...
        Route::get(LaravelLocalization::transRoute('routes.boat_on_demand'), ['as' => 'boat_on_demand', 'uses' => 'BodController@create']);
        Route::post(LaravelLocalization::transRoute('routes.boat_on_demand'), ['as' => 'boat_on_demand', 'uses' => 'BodController@store']);

        //=========================//
        //// search_notification routes...
        Route::post(LaravelLocalization::transRoute('routes.search_notification'), ['as' => 'search_notification', 'uses' => 'SearchController@notification']);

        //=========================//
        // Sell routes...
        Route::get(LaravelLocalization::transRoute('routes.sell'), ['as' => 'sell', 'uses' => 'SellController@create']);
        Route::post(LaravelLocalization::transRoute('routes.sell'), ['as' => 'sell', 'uses' => 'SellController@store']);

        //=========================//
        // manufacturers routes...
        Route::get(LaravelLocalization::transRoute('routes.manufacturers'), ['as' => 'manufacturers', 'uses' => 'PagesController@index']);

        //=========================//
        // manufacturers_engines routes...
        Route::get(LaravelLocalization::transRoute('routes.manufacturers_engines'), ['as' => 'manufacturers_engines', 'uses' => 'PagesController@index']);

        //=========================//
        // login routes...
        Route::get('login', function() {
            return redirect(LaravelLocalization::transRoute('routes.login'), 301);
        });
        Route::get(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
        Route::post(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

        //=========================//
        // Registration routes...
        ////Route::get('register', function() {
        ////    return redirect(LaravelLocalization::transRoute('routes.register'));
        ////});
        ////Route::get(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
        ////Route::post(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
        Route::get('register', function() {
            return redirect(LaravelLocalization::transRoute('routes.login'), 301);
        });
        Route::get(LaravelLocalization::transRoute('routes.register'), function() {
            return redirect(LaravelLocalization::transRoute('routes.login'), 301);
        });

        //=========================//
        // Password reset email request routes...
        Route::get(LaravelLocalization::transRoute('routes.password_email'), ['as' => 'password_email', 'uses' => 'Auth\PasswordController@getEmail']);
        // Password reset email request routes... with email
        Route::get(LaravelLocalization::transRoute('routes.password_email') . '/{email}', function($email) {
            return View::make('auth.passwords.email',['email'=>$email]);
        });
        Route::get('password/email', function() {
            /*//return redirect(LaravelLocalization::transRoute('routes.password_email'));//*/
            return View::make('auth.passwords.email');
        });
        Route::get('password/email/{email}', function($email) {
            /*//return redirect(LaravelLocalization::transRoute('routes.password_email') . '/' . $email);//*/
            return View::make('auth.passwords.email',['email'=>$email]);
        });
        Route::post(LaravelLocalization::transRoute('routes.password_email'), ['as' => 'password_email', 'uses' => 'Auth\PasswordController@postEmail']);

        //=========================//
        // Password reset routes...
        Route::get(LaravelLocalization::transRoute('routes.password_reset'), ['as' => 'password_reset', 'uses' => 'Auth\PasswordController@getReset']);
        // Password reset email request routes... with token
        Route::get(LaravelLocalization::transRoute('routes.password_reset') . '/{token}', function($token) {
            return View::make('auth.passwords.reset',['token'=>$token]);
        });
        // Password reset email request routes... with token + email
        Route::get(LaravelLocalization::transRoute('routes.password_reset') . '/{token}/{email}', function($token, $email) {
            return View::make('auth.passwords.reset',['token'=>$token,'email'=>$email]);
        });
        Route::get('password/reset', function() {
            return redirect(LaravelLocalization::transRoute('routes.password_email'), 301);
            //return View::make('auth.passwords.email',[]);
        });
        Route::get('password/reset/{token}', function($token) {
            //return redirect(LaravelLocalization::transRoute('routes.password_reset') . '/' . $token);
            return View::make('auth.passwords.reset',['token'=>$token]);
        });
        Route::get('password/reset/{token}/{email}', function($token, $email) {
            //return redirect(LaravelLocalization::transRoute('routes.password_reset') . '/' . $token . '/' . $email);
            return View::make('auth.passwords.reset',['token'=>$token,'email'=>$email]);
        });
        Route::post(LaravelLocalization::transRoute('routes.password_reset'), ['as' => 'password_reset', 'uses' => 'Auth\PasswordController@postReset']);

        //=========================//
        // Logout routes...
        Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
        Route::get(LaravelLocalization::transRoute('routes.logout'), ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        // Ajax routes

        Route::post('ajax-country', ['as' => 'ajax_country', 'uses' => 'AjaxController@AjaxCountry']);
        Route::post('ajax-countries', ['as' => 'ajax_countries', 'uses' => 'AjaxController@AjaxCountries']);

        Route::post('ajax-email', ['as' => 'ajax_email', 'uses' => 'AjaxController@AjaxEmail']);

        Route::post('ajax-country_contracts', ['as' => 'ajax_country_contracts', 'uses' => 'AjaxController@AjaxCountryContracts']);

        Route::post('ajax-newsletter', ['as' => 'ajax_newsletter', 'uses' => 'NewsletterController@store']);
        Route::post('ajax-enquiry', ['as' => 'ajax_enquiry', 'uses' => 'EnquiryController@store']);
        Route::post('ajax-bod', ['as' => 'ajax_bod', 'uses' => 'EnquiryController@bod']);

        Route::post('ajax-adstype', ['as' => 'ajax_adstype', 'uses' => 'AjaxController@AjaxAdsType']);

        Route::get('ajax-adstypes', ['as' => 'ajax_adstypes', 'uses' => 'AjaxController@AjaxAdsTypes']);
        Route::post('ajax-adstypes', ['as' => 'ajax_adstypes', 'uses' => 'AjaxController@AjaxAdsTypes']);

        Route::get('ajax-adstype_detail', ['as' => 'ajax_adstype_detail', 'uses' => 'AjaxController@AjaxAdsTypeDetail']);
        Route::post('ajax-adstype_detail', ['as' => 'ajax_adstype_detail', 'uses' => 'AjaxController@AjaxAdsTypeDetail']);

        Route::post('ajax-category', ['as' => 'ajax_category', 'uses' => 'AjaxController@AjaxCategory']);

        Route::get('ajax-categories', ['as' => 'ajax_categories', 'uses' => 'AjaxController@AjaxCategories']);
        Route::post('ajax-categories', ['as' => 'ajax_categories', 'uses' => 'AjaxController@AjaxCategories']);

        Route::get('ajax-gateway_categories', ['as' => 'ajax_gateway_categories', 'uses' => 'AjaxController@AjaxGateWayCategories']);
        Route::post('ajax-gateway_categories', ['as' => 'ajax_gateway_categories', 'uses' => 'AjaxController@AjaxGateWayCategories']);

        Route::post('ajax-subcategory', ['as' => 'ajax_subcategory', 'uses' => 'AjaxController@AjaxSubCategory']);

        Route::get('ajax-subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxSubCategories']);
        Route::post('ajax-subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxSubCategories']);

        Route::get('ajax-gateway_subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxGateWaySubCategories']);
        Route::post('ajax-gateway_subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxGateWaySubCategories']);

        Route::get('ajax-manufacturer', ['as' => 'ajax_manufacturer', 'uses' => 'AjaxController@AjaxManufacturer']);
        Route::post('ajax-manufacturer', ['as' => 'ajax_manufacturer', 'uses' => 'AjaxController@AjaxManufacturer']);

        Route::get('ajax-manufacturers', ['as' => 'ajax_manufacturers', 'uses' => 'AjaxController@AjaxManufacturers']);
        Route::post('ajax-manufacturers', ['as' => 'ajax_manufacturers', 'uses' => 'AjaxController@AjaxManufacturers']);

        Route::get('ajax-gateway_manufacturer', ['as' => 'ajax_gateway_manufacturer', 'uses' => 'AjaxController@AjaxGateWayManufacturer']);
        Route::post('ajax-gateway_manufacturer', ['as' => 'ajax_gateway_manufacturer', 'uses' => 'AjaxController@AjaxGateWayManufacturer']);

        Route::get('ajax-gateway_manufacturers', ['as' => 'ajax_gateway_manufacturers', 'uses' => 'AjaxController@AjaxGateWayManufacturers']);
        Route::post('ajax-gateway_manufacturers', ['as' => 'ajax_gateway_manufacturers', 'uses' => 'AjaxController@AjaxGateWayManufacturers']);

        Route::get('ajax-model', ['as' => 'ajax_model', 'uses' => 'AjaxController@AjaxModel']);
        Route::post('ajax-model', ['as' => 'ajax_model', 'uses' => 'AjaxController@AjaxModel']);

        Route::get('ajax-models', ['as' => 'ajax_models', 'uses' => 'AjaxController@AjaxModels']);
        Route::post('ajax-models', ['as' => 'ajax_models', 'uses' => 'AjaxController@AjaxModels']);

        Route::get('ajax-gateway_model', ['as' => 'ajax_gateway_model', 'uses' => 'AjaxController@AjaxGateWayModel']);
        Route::post('ajax-gateway_model', ['as' => 'ajax_gateway_model', 'uses' => 'AjaxController@AjaxGateWayModel']);

        Route::get('ajax-gateway_models', ['as' => 'ajax_gateway_models', 'uses' => 'AjaxController@AjaxGateWayModels']);
        Route::post('ajax-gateway_models', ['as' => 'ajax_gateway_models', 'uses' => 'AjaxController@AjaxGateWayModels']);

        //Route::post('ajax-manufacturer_engine', ['as' => 'ajax_manufacturer_engine', 'uses' => 'AjaxController@AjaxManufacturerEngine']);
        //Route::post('ajax-manufacturers_engines', ['as' => 'ajax_manufacturers_engines', 'uses' => 'AjaxController@AjaxManufacturersEngines']);

        //Route::post('ajax-gateway_manufacturer_engine', ['as' => 'ajax_gateway_manufacturer_engine', 'uses' => 'AjaxController@AjaxGateWayManufacturerEngine']);
        //Route::post('ajax-gateway_manufacturers_engines', ['as' => 'ajax_gateway_manufacturers_engines', 'uses' => 'AjaxController@AjaxGateWayManufacturersEngines']);

        //Route::post('ajax-model_engine', ['as' => 'ajax_model_engine', 'uses' => 'AjaxController@AjaxModelEngine']);
        //Route::post('ajax-models_engines', ['as' => 'ajax_models_engines', 'uses' => 'AjaxController@AjaxModelsEngines']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        // for_sale routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale'), ['as' => 'for_sale', 'uses' => 'ForsaleController@showAds']);
        Route::post(LaravelLocalization::transRoute('routes.for_sale'), ['as' => 'for_sale', 'uses' => 'ForsaleController@showAds']);

        // for_sale manufacturers routes...
        //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers'), ['as' => 'manufacturers', 'uses' => 'PagesController@index']);
        /*Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers'), function() {
            return redirect(LaravelLocalization::transRoute('routes.manufacturers'));
        }*/
        // manufacturers routes...
        //##Route::get(LaravelLocalization::transRoute('routes.manufacturers') . '/{manufacturers}', ['as' => 'manufacturer_detail', 'uses' => 'ManufacturerController@index']);

        // for_sale by manufacturers routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer') . '/{manufacturers}', ['as' => 'showAdsByManufacturer', 'uses' => 'ForsaleController@showAds']);
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer'), function() {
            return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
        });

        // for_sale showAdsByModels routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model') . '/{manufacturers}/{models}', ['as' => 'showAdsByModel', 'uses' => 'ForsaleController@showAds']);
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model') . '/{manufacturers}', function($manufacturers) {
            return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer') . '/' . $manufacturers, 301);
        });
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model'), function() {
            return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
        });
        //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.models') . '/{models}', ['as' => 'showAdsByModel', 'uses' => 'ForsaleController@showAds']);

        // for_sale manufacturers_engines routes...
        //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines'), ['as' => 'manufacturers_engines', 'uses' => 'PagesController@index']);
        /*Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines'), function() {
            return redirect(LaravelLocalization::transRoute('routes.manufacturers_engines'));
        }*/
        // manufacturers engines routes...
        //##Route::get(LaravelLocalization::transRoute('routes.manufacturers_engines') . '/{manufacturersengines}', ['as' => 'manufacturer_engine_detail', 'uses' => 'ManufacturerEngineController@index']);

        // for_sale showAdsByManufacturerEngines routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine') . '/{manufacturersengines}', ['as' => 'showAdsByManufacturerEngine', 'uses' => 'ForsaleController@showAds']);
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine'), function() {
            return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
        });

        // for_sale showAdsByModels routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine') . '/{manufacturersengines}/{modelsengines}', ['as' => 'showAdsByModelEngine', 'uses' => 'ForsaleController@showAds']);
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine') . '/{manufacturersengines}', function($manufacturersengines) {
            return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine') . '/' . $manufacturersengines, 301);
        });
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine'), function() {
            return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
        });
        //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.models_engines') . '/{modelsengines}', ['as' => 'showAdsByModelEngine', 'uses' => 'ForsaleController@showAds']);

        // for_sale showAdsByType  routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}', ['as' => 'showAdsByType', 'uses' => 'ForsaleController@showAds']);
        Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}', ['as' => 'showAdsByType', 'uses' => 'ForsaleController@showAds']);

        // for_sale showAdsByCategory  routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}', ['as' => 'showAdsByCategory', 'uses' => 'ForsaleController@showAds']);
        Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}', ['as' => 'showAdsByCategory', 'uses' => 'ForsaleController@showAds']);

        // for_sale showAdsBySubcategory  routes...
        Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}/{subcategories}', ['as' => 'showAdsBySubcategory', 'uses' => 'ForsaleController@showAds']);
        Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}/{subcategories}', ['as' => 'showAdsBySubcategory', 'uses' => 'ForsaleController@showAds']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        // buy routes...
        Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}/{ad_id}/{more}', function($adstypes, $manufacturers, $models, $ad_id, $more=''){
            return redirect(LaravelLocalization::transRoute('routes.buy') . '/' . $adstypes . '/' . $manufacturers . '/' . $models . '/' . $ad_id, 301);
        })->where('more', '.*');

            // @TODO update Xpath to not have this aberate url
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers_models}/{ad_id}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail'])->where(['ad_id' => '[0-9]+']);
            //})->where(['ad_id' => '[0-9]+', 'manufacturers_models' => '[A-Za-z0-9_-]+']);

            // buy adstypes/manufacturers/models/ad_id routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}/{ad_id}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);

            // buy adstypes/manufacturers/modelsroutes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);

            // buy adstypes/manufacturers routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);

            // buy adstypes/query routes...
            /*Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{query}', function($adstypes, $query=''){
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . $adstypes . '?query=' . str_slug(str_replace('/',' ', $query), ' '), 301);
                //return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers') . '/' . $query, 301);
            })->where('query', '.*');*/

            // buy adstypes routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}', function($adstypes) {
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . $adstypes, 301);
            });

            Route::get(LaravelLocalization::transRoute('routes.buy'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //=========================//
        // Dashboard routes...
        Route::get(LaravelLocalization::transRoute('routes.dashboard'), ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

        //=========================//
        // Dashboard Edit Bod routes...
        Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), function() {
            return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
        });
        Route::post(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), ['as' => 'dashboard_edit_bod', 'uses' => 'DashboardController@editBod']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), ['as' => 'dashboard_edit_bod', 'uses' => 'DashboardController@updateBod']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_unpublish_bod'), ['as' => 'dashboard_unpublish_bod', 'uses' => 'DashboardController@unpublishBod']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_reactivate_bod'), ['as' => 'dashboard_reactivate_bod', 'uses' => 'DashboardController@reactivateBod']);

        //=========================//
        // Dashboard Edit Ads routes...
        Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), function() {
            return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
        });
        Route::post(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), ['as' => 'dashboard_edit_ads', 'uses' => 'DashboardController@editAds']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), ['as' => 'dashboard_edit_ads', 'uses' => 'DashboardController@updateAds']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_unpublish_ads'), ['as' => 'dashboard_unpublish_ads', 'uses' => 'DashboardController@unpublishAds']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_reactivate_ads'), ['as' => 'dashboard_reactivate_ads', 'uses' => 'DashboardController@reactivateAds']);

        //=========================//
        // Dashboard Edit Customer routes...
        Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_customer'), ['as' => 'dashboard_edit_customer', 'uses' => 'DashboardController@editCustomer']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_customer'), ['as' => 'dashboard_edit_customer', 'uses' => 'DashboardController@updateCustomer']);

        Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_account'), ['as' => 'dashboard_edit_account', 'uses' => 'DashboardController@editAccount']);
        Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_account'), ['as' => 'dashboard_edit_account', 'uses' => 'DashboardController@updateAccount']);

        Route::get(LaravelLocalization::transRoute('routes.dashboard_change_password') . '/{email}', function($email) {
            return View::make('dashboard_change_password',['email'=>$email]);
        });
        Route::get(LaravelLocalization::transRoute('routes.dashboard_change_password'), function() {
            return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
        });
        Route::post(LaravelLocalization::transRoute('routes.dashboard_change_password'), ['as' => 'dashboard_change_password', 'uses' => 'DashboardController@updatePassword']);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    });
} else {
    //**************************//
    // MIDDLEWARE : web, domain //
    //**************************//
    $callbackRoutes = function() {
        //***********************************************************************//
        // MIDDLEWARE : web, domain, localeSessionRedirect, localizationRedirect //
        //***********************************************************************//

        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect']
            ,'middleware' => [ 'localize' ] // Route translate middleware
        ], function() {

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            Route::get('/robots.txt', function($subdomain, $domain)
            {
                return redirect('/robots/robots_' . $subdomain . '.' . $domain . '.txt');
            });

            Route::get('/sitemap.xml', function($subdomain, $domain)
            {
                return redirect('/sitemaps/sitemap_' . $subdomain . '.' . $domain . '.xml');
            });

            Route::get('/hypes.txt', function($subdomain, $domain)
            {
                return redirect('/hypes/hypes_' . $subdomain . '.' . $domain . '.txt');
            });

            //=========================//
            // Errors routes...
            Route::get('403', function($subdomain, $domain)
            {
                abort(403);
            });
            Route::get('404', function($subdomain, $domain)
            {
                abort(404);
            });
            Route::get('500', function($subdomain, $domain)
            {
                abort(500);
            });
            Route::get('503', function($subdomain, $domain)
            {
                abort(503);
            });

            $host = isset($_SERVER) && array_key_exists('SERVER_NAME', $_SERVER) ? $_SERVER['SERVER_NAME'] : (isset($_SERVER) && array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '');
            //if (preg_match('/' . LaravelLocalization::transRoute('routes.dealers') . '/', $host)) {
            if (preg_match('/dealers/', $host)) {

                Route::get('/{country_code}/{rewrite_url}', ['as' => 'dealer_details', 'uses' => 'DealersController@index']);

                Route::get('/{country_code}/', function($country_code)
                {
                    //$url_redirect = LaravelLocalization::transRoute('routes.for_sale');
                    //$url_redirect = 'https://www.youboat.com';
                    $url_redirect = 'https://' . (strlen($country_code) == 2 ? $country_code : 'www') . '.youboat.com';
                    return redirect($url_redirect, 301);
                });
                Route::get('/', function() {
                    return redirect('https://www.youboat.com', 301);
                });
            } else {
                //=========================//
                // Homepage route...
                //Route::get(LaravelLocalization::transRoute('routes.homepage'), ['as' => 'homepage', 'uses' => 'HomeController@index']);
                Route::get('/', ['as' => 'homepage', 'uses' => 'HomeController@index']);
            }

            //=========================//
            // Ad Not Found routes...
            Route::get(LaravelLocalization::transRoute('routes.ad_not_found'), ['as' => 'ad_not_found', 'uses' => 'AdNotFoundController@index']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            // Landing routes...
            //Route::get('/', ['as' => 'landing', 'uses' => 'LandingController@index']);
            Route::get(LaravelLocalization::transRoute('routes.landing'), ['as' => 'landing', 'uses' => 'LandingController@index']);
            //Route::post('/', ['as' => 'landing', 'uses' => 'LandingController@store']);
            Route::post(LaravelLocalization::transRoute('routes.landing'), ['as' => 'landing', 'uses' => 'LandingController@store']);

            //=========================//
            // Landing Contact routes...
            Route::get(LaravelLocalization::transRoute('routes.landingcontact'), ['as' => 'landingcontact', 'uses' => 'LandingContactController@create']);
            Route::post(LaravelLocalization::transRoute('routes.landingcontact'), ['as' => 'landingcontact', 'uses' => 'LandingContactController@store']);

            //=========================//
            // Contact routes...
            Route::get(LaravelLocalization::transRoute('routes.contact'), ['as' => 'contact', 'uses' => 'ContactController@create']);
            Route::post(LaravelLocalization::transRoute('routes.contact'), ['as' => 'contact', 'uses' => 'ContactController@store']);

            //=========================//
            // News routes...
            //Route::get(LaravelLocalization::transRoute('routes.news_detail'), ['as' => 'news_detail', 'uses' => 'NewsController@index']);
            Route::get(LaravelLocalization::transRoute('routes.news_detail'), function() {
                return redirect(LaravelLocalization::transRoute('routes.home'), 301);
            });
            Route::get(LaravelLocalization::transRoute('routes.news_detail') . '/{country_code}/{date}/{title_slug}', ['as' => 'news_detail', 'uses' => 'NewsController@showNewsDetail']);
            Route::get(LaravelLocalization::transRoute('routes.news_create'), ['as' => 'news_create', 'uses' => 'NewsController@create']);
            Route::post(LaravelLocalization::transRoute('routes.news_create'), ['as' => 'news_create', 'uses' => 'NewsController@store']);

            //=========================//
            // Newsletter routes...
            Route::get(LaravelLocalization::transRoute('routes.newsletter'), ['as' => 'newsletter', 'uses' => 'NewsletterController@create']);
            Route::get(LaravelLocalization::transRoute('routes.newsletter') . '/{email}', function($subdomain, $domain, $email) {
                return View::make('newsletter',['email'=>$email]);
            });
            Route::post(LaravelLocalization::transRoute('routes.newsletter'), ['as' => 'newsletter', 'uses' => 'NewsletterController@store']);

            //=========================//
            // Welcome routes...
            Route::get(LaravelLocalization::transRoute('routes.welcome'), ['as' => 'welcome', 'uses' => 'PagesController@index']);

            //=========================//
            // About routes...
            Route::get(LaravelLocalization::transRoute('routes.about'), ['as' => 'about', 'uses' => 'PagesController@index']);

            //=========================//
            // CGV routes...
            Route::get(LaravelLocalization::transRoute('routes.cgv'), ['as' => 'cgv', 'uses' => 'PagesController@index']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            //// boat_on_demand routes...
            Route::get(LaravelLocalization::transRoute('routes.boat_on_demand'), ['as' => 'boat_on_demand', 'uses' => 'BodController@create']);
            Route::post(LaravelLocalization::transRoute('routes.boat_on_demand'), ['as' => 'boat_on_demand', 'uses' => 'BodController@store']);

            //=========================//
            //// search_notification routes...
            Route::post(LaravelLocalization::transRoute('routes.search_notification'), ['as' => 'search_notification', 'uses' => 'SearchController@notification']);

            //=========================//
            // Sell routes...
            Route::get(LaravelLocalization::transRoute('routes.sell'), ['as' => 'sell', 'uses' => 'SellController@create']);
            Route::post(LaravelLocalization::transRoute('routes.sell'), ['as' => 'sell', 'uses' => 'SellController@store']);

            //=========================//
            // manufacturers routes...
            Route::get(LaravelLocalization::transRoute('routes.manufacturers'), ['as' => 'manufacturers', 'uses' => 'PagesController@index']);

            //=========================//
            // manufacturers_engines routes...
            Route::get(LaravelLocalization::transRoute('routes.manufacturers_engines'), ['as' => 'manufacturers_engines', 'uses' => 'PagesController@index']);

            //=========================//
            // login routes...
            Route::get('login', function() {
                return redirect(LaravelLocalization::transRoute('routes.login'), 301);
            });
            //Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
            Route::get(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
            Route::post(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

            //=========================//
            // Registration routes...
            ////Route::get('register', function() {
            ////    return redirect(LaravelLocalization::transRoute('routes.register'));
            ////});
            ////Route::get(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
            ////Route::post(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
            Route::get('register', function() {
                return redirect(LaravelLocalization::transRoute('routes.login'), 301);
            });
            Route::get(LaravelLocalization::transRoute('routes.register'), function() {
                return redirect(LaravelLocalization::transRoute('routes.login'), 301);
            });

            //=========================//
            // Password reset email request routes...
            Route::get(LaravelLocalization::transRoute('routes.password_email'), ['as' => 'password_email', 'uses' => 'Auth\PasswordController@getEmail']);
            // Password reset email request routes... with email
            Route::get(LaravelLocalization::transRoute('routes.password_email') . '/{email}', function($subdomain, $domain, $email) {
                return View::make('auth.passwords.email',['email'=>$email]);
            });
            Route::get('password/email', function($subdomain, $domain) {
                /*//return redirect(LaravelLocalization::transRoute('routes.password_email'));//*/
                return View::make('auth.passwords.email');
            });
            Route::get('password/email/{email}', function($subdomain, $domain, $email) {
                /*//return redirect(LaravelLocalization::transRoute('routes.password_email') . '/' . $email);//*/
                return View::make('auth.passwords.email',['email'=>$email]);
            });
            Route::post(LaravelLocalization::transRoute('routes.password_email'), ['as' => 'password_email', 'uses' => 'Auth\PasswordController@postEmail']);

            //=========================//
            // Password reset routes...
            Route::get(LaravelLocalization::transRoute('routes.password_reset'), ['as' => 'password_reset', 'uses' => 'Auth\PasswordController@getReset']);
            // Password reset email request routes... with token
            Route::get(LaravelLocalization::transRoute('routes.password_reset') . '/{token}', function($subdomain, $domain, $token) {
                return View::make('auth.passwords.reset',['token'=>$token]);
            });
            // Password reset email request routes... with token + email
            Route::get(LaravelLocalization::transRoute('routes.password_reset') . '/{token}/{email}', function($subdomain, $domain, $token, $email) {
                return View::make('auth.passwords.reset',['token'=>$token,'email'=>$email]);
            });
            Route::get('password/reset', function() {
                return redirect(LaravelLocalization::transRoute('routes.password_email'), 301);
                //return View::make('auth.passwords.email',[]);
            });
            Route::get('password/reset/{token}', function($token) {
                //return redirect(LaravelLocalization::transRoute('routes.password_reset') . '/' . $token);
                return View::make('auth.passwords.reset',['token'=>$token]);
            });
            Route::get('password/reset/{token}/{email}', function($subdomain, $domain, $token, $email) {
                //return redirect(LaravelLocalization::transRoute('routes.password_reset') . '/' . $token . '/' . $email);
                return View::make('auth.passwords.reset',['token'=>$token,'email'=>$email]);
            });
            Route::post(LaravelLocalization::transRoute('routes.password_reset'), ['as' => 'password_reset', 'uses' => 'Auth\PasswordController@postReset']);

            //=========================//
            // Logout routes...
            Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
            Route::get(LaravelLocalization::transRoute('routes.logout'), ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            // Ajax routes

            Route::post('ajax-country', ['as' => 'ajax_country', 'uses' => 'AjaxController@AjaxCountry']);
            Route::post('ajax-countries', ['as' => 'ajax_countries', 'uses' => 'AjaxController@AjaxCountries']);

            Route::post('ajax-email', ['as' => 'ajax_email', 'uses' => 'AjaxController@AjaxEmail']);

            Route::post('ajax-country_contracts', ['as' => 'ajax_country_contracts', 'uses' => 'AjaxController@AjaxCountryContracts']);

            Route::post('ajax-newsletter', ['as' => 'ajax_newsletter', 'uses' => 'NewsletterController@store']);
            Route::post('ajax-enquiry', ['as' => 'ajax_enquiry', 'uses' => 'EnquiryController@store']);
            Route::post('ajax-bod', ['as' => 'ajax_bod', 'uses' => 'EnquiryController@bod']);

            Route::post('ajax-adstype', ['as' => 'ajax_adstype', 'uses' => 'AjaxController@AjaxAdsType']);

            Route::get('ajax-adstypes', ['as' => 'ajax_adstypes', 'uses' => 'AjaxController@AjaxAdsTypes']);
            Route::post('ajax-adstypes', ['as' => 'ajax_adstypes', 'uses' => 'AjaxController@AjaxAdsTypes']);

            Route::get('ajax-adstype_detail', ['as' => 'ajax_adstype_detail', 'uses' => 'AjaxController@AjaxAdsTypeDetail']);
            Route::post('ajax-adstype_detail', ['as' => 'ajax_adstype_detail', 'uses' => 'AjaxController@AjaxAdsTypeDetail']);

            Route::post('ajax-category', ['as' => 'ajax_category', 'uses' => 'AjaxController@AjaxCategory']);

            Route::get('ajax-categories', ['as' => 'ajax_categories', 'uses' => 'AjaxController@AjaxCategories']);
            Route::post('ajax-categories', ['as' => 'ajax_categories', 'uses' => 'AjaxController@AjaxCategories']);

            Route::get('ajax-gateway_categories', ['as' => 'ajax_gateway_categories', 'uses' => 'AjaxController@AjaxGateWayCategories']);
            Route::post('ajax-gateway_categories', ['as' => 'ajax_gateway_categories', 'uses' => 'AjaxController@AjaxGateWayCategories']);

            Route::post('ajax-subcategory', ['as' => 'ajax_subcategory', 'uses' => 'AjaxController@AjaxSubCategory']);

            Route::get('ajax-subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxSubCategories']);
            Route::post('ajax-subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxSubCategories']);

            Route::get('ajax-gateway_subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxGateWaySubCategories']);
            Route::post('ajax-gateway_subcategories', ['as' => 'ajax_subcategories', 'uses' => 'AjaxController@AjaxGateWaySubCategories']);

            Route::get('ajax-manufacturer', ['as' => 'ajax_manufacturer', 'uses' => 'AjaxController@AjaxManufacturer']);
            Route::post('ajax-manufacturer', ['as' => 'ajax_manufacturer', 'uses' => 'AjaxController@AjaxManufacturer']);

            Route::get('ajax-manufacturers', ['as' => 'ajax_manufacturers', 'uses' => 'AjaxController@AjaxManufacturers']);
            Route::post('ajax-manufacturers', ['as' => 'ajax_manufacturers', 'uses' => 'AjaxController@AjaxManufacturers']);

            Route::get('ajax-gateway_manufacturer', ['as' => 'ajax_gateway_manufacturer', 'uses' => 'AjaxController@AjaxGateWayManufacturer']);
            Route::post('ajax-gateway_manufacturer', ['as' => 'ajax_gateway_manufacturer', 'uses' => 'AjaxController@AjaxGateWayManufacturer']);

            Route::get('ajax-gateway_manufacturers', ['as' => 'ajax_gateway_manufacturers', 'uses' => 'AjaxController@AjaxGateWayManufacturers']);
            Route::post('ajax-gateway_manufacturers', ['as' => 'ajax_gateway_manufacturers', 'uses' => 'AjaxController@AjaxGateWayManufacturers']);

            Route::get('ajax-model', ['as' => 'ajax_model', 'uses' => 'AjaxController@AjaxModel']);
            Route::post('ajax-model', ['as' => 'ajax_model', 'uses' => 'AjaxController@AjaxModel']);

            Route::get('ajax-models', ['as' => 'ajax_models', 'uses' => 'AjaxController@AjaxModels']);
            Route::post('ajax-models', ['as' => 'ajax_models', 'uses' => 'AjaxController@AjaxModels']);

            Route::get('ajax-gateway_model', ['as' => 'ajax_gateway_model', 'uses' => 'AjaxController@AjaxGateWayModel']);
            Route::post('ajax-gateway_model', ['as' => 'ajax_gateway_model', 'uses' => 'AjaxController@AjaxGateWayModel']);

            Route::get('ajax-gateway_models', ['as' => 'ajax_gateway_models', 'uses' => 'AjaxController@AjaxGateWayModels']);
            Route::post('ajax-gateway_models', ['as' => 'ajax_gateway_models', 'uses' => 'AjaxController@AjaxGateWayModels']);

            //Route::post('ajax-manufacturer_engine', ['as' => 'ajax_manufacturer_engine', 'uses' => 'AjaxController@AjaxManufacturerEngine']);
            //Route::post('ajax-manufacturers_engines', ['as' => 'ajax_manufacturers_engines', 'uses' => 'AjaxController@AjaxManufacturersEngines']);

            //Route::post('ajax-gateway_manufacturer_engine', ['as' => 'ajax_gateway_manufacturer_engine', 'uses' => 'AjaxController@AjaxGateWayManufacturerEngine']);
            //Route::post('ajax-gateway_manufacturers_engines', ['as' => 'ajax_gateway_manufacturers_engines', 'uses' => 'AjaxController@AjaxGateWayManufacturersEngines']);

            //Route::post('ajax-model_engine', ['as' => 'ajax_model_engine', 'uses' => 'AjaxController@AjaxModelEngine']);
            //Route::post('ajax-models_engines', ['as' => 'ajax_models_engines', 'uses' => 'AjaxController@AjaxModelsEngines']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            // for_sale routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale'), ['as' => 'for_sale', 'uses' => 'ForsaleController@showAds']);
            Route::post(LaravelLocalization::transRoute('routes.for_sale'), ['as' => 'for_sale', 'uses' => 'ForsaleController@showAds']);

            // for_sale manufacturers routes...
            //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers'), ['as' => 'manufacturers', 'uses' => 'PagesController@index']);
            /*Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers'), function($subdomain, $domain) {
                return redirect(LaravelLocalization::transRoute('routes.manufacturers'));
            }*/
            // manufacturers routes...
            //##Route::get(LaravelLocalization::transRoute('routes.manufacturers') . '/{manufacturers}', ['as' => 'manufacturer_detail', 'uses' => 'ManufacturerController@index']);

            // for_sale by manufacturers routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer') . '/{manufacturers}', ['as' => 'showAdsByManufacturer', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });

            // for_sale showAdsByModels routes...
            //##Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers') . '/{manufacturers}/{models}', ['as' => 'showAdsByModel', 'uses' => 'ForsaleController@showAds']);

            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model') . '/{manufacturers}/{models}', ['as' => 'showAdsByModel', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model') . '/{manufacturers}', function($manufacturers) {
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer') . '/' . $manufacturers, 301);
            });
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });
            //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.models') . '/{models}', ['as' => 'showAdsByModel', 'uses' => 'ForsaleController@showAds']);

            // for_sale manufacturers_engines routes...
            //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines'), ['as' => 'manufacturers_engines', 'uses' => 'PagesController@index']);
            /*Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines'), function($subdomain, $domain) {
                return redirect(LaravelLocalization::transRoute('routes.manufacturers_engines'));
            }*/
            // manufacturers engines routes...
            //##Route::get(LaravelLocalization::transRoute('routes.manufacturers_engines') . '/{manufacturersengines}', ['as' => 'manufacturer_engine_detail', 'uses' => 'ManufacturerEngineController@index']);

            // for_sale showAdsByManufacturerEngines routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines') . '/{manufacturersengines}', ['as' => 'showAdsByManufacturerEngine', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine') . '/{manufacturersengines}', ['as' => 'showAdsByManufacturerEngine', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });

            // for_sale showAdsByModels routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers_engines') . '/{manufacturersengines}/{modelsengines}', ['as' => 'showAdsByModelEngine', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine') . '/{manufacturersengines}/{modelsengines}', ['as' => 'showAdsByModelEngine', 'uses' => 'ForsaleController@showAds']);
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine') . '/{manufacturersengines}', function($manufacturersengines) {
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_manufacturer_engine') . '/' . $manufacturersengines, 301);
            });
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.by_model_engine'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });
            //Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.models_engines') . '/{modelsengines}', ['as' => 'showAdsByModelEngine', 'uses' => 'ForsaleController@showAds']);

            // for_sale showAdsByType  routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}', ['as' => 'showAdsByType', 'uses' => 'ForsaleController@showAds']);
            Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}', ['as' => 'showAdsByType', 'uses' => 'ForsaleController@showAds']);

            // for_sale showAdsByCategory  routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}', ['as' => 'showAdsByCategory', 'uses' => 'ForsaleController@showAds']);
            Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}', ['as' => 'showAdsByCategory', 'uses' => 'ForsaleController@showAds']);

            // for_sale showAdsBySubcategory  routes...
            Route::get(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}/{subcategories}', ['as' => 'showAdsBySubcategory', 'uses' => 'ForsaleController@showAds']);
            Route::post(LaravelLocalization::transRoute('routes.for_sale') . '/{adstypes}/{categories}/{subcategories}', ['as' => 'showAdsBySubcategory', 'uses' => 'ForsaleController@showAds']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            // buy routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}/{ad_id}/{more}', function($adstypes, $manufacturers, $models, $ad_id, $more=''){
                return redirect(LaravelLocalization::transRoute('routes.buy') . '/' . $adstypes . '/' . $manufacturers . '/' . $models . '/' . $ad_id, 301);
            })->where('more', '.*');

            // @TODO update Xpath to not have this aberate url
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers_models}/{ad_id}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail'])->where(['ad_id' => '[0-9]+']);
            //})->where(['ad_id' => '[0-9]+', 'manufacturers_models' => '[A-Za-z0-9_-]+']);

            // buy adstypes/manufacturers/models/ad_id routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}/{ad_id}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);

            // buy adstypes/manufacturers/modelsroutes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}/{models}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);

            // buy adstypes/manufacturers routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{manufacturers}', ['as' => 'show_ad_detail', 'uses' => 'ForsaleController@showAdDetail']);


            // buy adstypes/query routes...
            /*Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}/{query}', function($adstypes, $query=''){
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . $adstypes . '?query=' . str_slug(str_replace('/',' ', $query), ' '), 301);
                //return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . LaravelLocalization::transRoute('routes.manufacturers') . '/' . $query, 301);
            })->where('query', '.*');*/

            // buy adstypes routes...
            Route::get(LaravelLocalization::transRoute('routes.buy') . '/{adstypes}', function($adstypes) {
                return redirect(LaravelLocalization::transRoute('routes.for_sale') . '/' . $adstypes, 301);
            });

            Route::get(LaravelLocalization::transRoute('routes.buy'), function() {
                return redirect(LaravelLocalization::transRoute('routes.for_sale'), 301);
            });

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //=========================//
            // Dashboard routes...
            Route::get(LaravelLocalization::transRoute('routes.dashboard'), ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

            //=========================//
            // Dashboard Edit Bod routes...
            Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), function() {
                return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
            });
            Route::post(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), ['as' => 'dashboard_edit_bod', 'uses' => 'DashboardController@editBod']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_bod'), ['as' => 'dashboard_edit_bod', 'uses' => 'DashboardController@updateBod']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_unpublish_bod'), ['as' => 'dashboard_unpublish_bod', 'uses' => 'DashboardController@unpublishBod']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_reactivate_bod'), ['as' => 'dashboard_reactivate_bod', 'uses' => 'DashboardController@reactivateBod']);

            //=========================//
            // Dashboard Edit Ads routes...
            Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), function() {
                return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
            });
            Route::post(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), ['as' => 'dashboard_edit_ads', 'uses' => 'DashboardController@editAds']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_ads'), ['as' => 'dashboard_edit_ads', 'uses' => 'DashboardController@updateAds']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_unpublish_ads'), ['as' => 'dashboard_unpublish_ads', 'uses' => 'DashboardController@unpublishAds']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_reactivate_ads'), ['as' => 'dashboard_reactivate_ads', 'uses' => 'DashboardController@reactivateAds']);

            //=========================//
            // Dashboard Edit Customer routes...
            Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_customer'), ['as' => 'dashboard_edit_customer', 'uses' => 'DashboardController@editCustomer']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_customer'), ['as' => 'dashboard_edit_customer', 'uses' => 'DashboardController@updateCustomer']);

            Route::get(LaravelLocalization::transRoute('routes.dashboard_edit_account'), ['as' => 'dashboard_edit_account', 'uses' => 'DashboardController@editAccount']);
            Route::patch(LaravelLocalization::transRoute('routes.dashboard_edit_account'), ['as' => 'dashboard_edit_account', 'uses' => 'DashboardController@updateAccount']);

            Route::get(LaravelLocalization::transRoute('routes.dashboard_change_password') . '/{email}', function($subdomain, $domain, $email) {
                return View::make('dashboard_change_password',['email'=>$email]);
            });
            Route::get(LaravelLocalization::transRoute('routes.dashboard_change_password'), function() {
                return redirect(LaravelLocalization::transRoute('routes.dashboard'), 301);
            });
            Route::post(LaravelLocalization::transRoute('routes.dashboard_change_password'), ['as' => 'dashboard_change_password', 'uses' => 'DashboardController@updatePassword']);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        });
    };

    Route::pattern('domain2', '(www.youboat)');
    Route::group(['domain2' => '{domain}.{subdomain}'], $callbackRoutes);

    Route::pattern('domain', '(youboat.com)');
    Route::group(['domain' => '{subdomain}.{domain}'], $callbackRoutes);
}
