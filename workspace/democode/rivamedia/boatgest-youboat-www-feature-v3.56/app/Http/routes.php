<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/make_pwd/{password_cleared}', function($password_cleared) {
    return bcrypt($password_cleared);
});

/*Route::group(['middleware' => 'web,guest'], function () {
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');
});*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
        //,'middleware' => [ 'localize' ] // Route translate middleware
], function() {
        Route::group([
            'middleware' => ['guest','web']
        ], function () {
                //Route::get('/', 'HomeController@index');
                Route::get('/', function () {
                        return redirect(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.dashboard'), 301);
                });
                Route::get('home', function () {
                        return redirect(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.dashboard'), 301);
                });
                Route::get(config('quickadmin.route'), function () {
                        return redirect(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.dashboard'), 301);
                });
                Route::get('welcome', ['as' => 'welcome', 'uses' => 'PagesController@welcome']);
                Route::get('about', ['as' => 'about', 'uses' => 'PagesController@about']);
                Route::get('scrap', ['as' => 'scrap', 'uses' => 'PagesController@scrap']);

                //Route::get('contact', ['as' => 'contact', 'uses' => 'ContactController@create']);
                Route::get(LaravelLocalization::transRoute('routes.contact'), ['as' => 'contact', 'uses' => 'ContactController@create']);
                Route::post('contact_store', ['as' => 'contact_store', 'uses' => 'ContactController@store']);
                Route::post('contact_login', ['as' => 'contact_login', 'uses' => 'ContactController@store']);
        });

        Route::group([
            'namespace'  => 'boatgestAdmin',
            'middleware' => ['web', 'role']
        ], function () {
                Route::resource('users', 'UsersController');
                Route::resource('roles', 'RolesController');
                /*Route::get(config('quickadmin.route'), function () {
                        return redirect()->route(config('quickadmin.route') .'.dashboard.index');
                });*/
                Route::get(config('quickadmin.route') . '/bodcaracts/{id}', ['as' => 'BodCaractsDetail', 'uses' => 'BodCaractsController@detail']);
                Route::get(config('quickadmin.route') . '/enquiry/{id}', ['as' => 'EnquiryDetail', 'uses' => 'EnquiryController@detail']);
                //Route::get(config('quickadmin.route') . '/news/{id}', ['as' => 'NewsDetail', 'uses' => 'NewsController@detail']);

                Route::get(config('quickadmin.route') . '/statistics/ad-{ad_id}', ['as' => 'adStats', 'uses' => 'StatisticsController@adStatsEvents'])->where(['ad_id' => '^ad-[0-9]+']);
                //Route::get(config('quickadmin.route') . '/statistics/{ad_id}', ['as' => 'adStats', 'uses' => 'StatisticsController@adStatsEvents'])->where(['ad_id' => '[A-Za-z_-]+\/[A-Za-z_-]+\/[A-Za-z0-9_-]+\/[A-Za-z0-9_-]+\/.*?([0-9]+)$']);
                Route::get(config('quickadmin.route') . '/statistics/{path}', ['as' => 'boatgest-admin.statistics.show', 'uses' => 'StatisticsController@show'])->where('path', '.*');
                //Route::get(config('quickadmin.route') . '/statistics/{path}', ['as' => 'boatgest-admin.statistics.show', 'uses' => 'StatisticsController@show'])->where(['path' => '[A-Za-z0-9_-]+']);
                //Route::get(config('quickadmin.route') . '/statistics/{path}', ['as' => 'boatgest-admin.statistics.show', 'uses' => 'StatisticsController@show'])->where(['path' => '([A-Za-z0-9_-]+)|([A-Za-z_-]+\/[A-Za-z_-]+\/[A-Za-z0-9_-]+\/[A-Za-z0-9_-]+\/[0-9]+)']);
        });
        Route::group([
            'namespace'  => 'boatgestAdmin',
            'middleware' => 'web'
        ], function () {
                //Route::post('boatgest-admin/models', ['as' => 'models_list', 'uses' => 'ModelsController@index']);
                //Route::get('logout-and-reset-password/{email}', ['as' => 'logout_and_reset_password', 'uses' => 'UsersController@logoutResetPassword']);
                Route::get(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.logout_and_reset_password') . '/{email}', ['as' => 'logout_and_reset_password', 'uses' => 'UsersController@logoutResetPassword']);
                Route::get(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password') . '/{email}', function($email) {
                        return View::make(config('quickadmin.route') . '.change_password',['email'=>$email]);
                });
                Route::post(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password'), ['as' => 'change_password', 'uses' => 'UsersController@updatePassword']);

                Route::get('email-credential', ['as' => 'email_credential', 'uses' => 'UsersController@emailCredential']);
                Route::get('email-credential/{id}', ['as' => 'email_credential', 'uses' => 'UsersController@emailCredential']);
        });
        Route::group([
            'middleware' => 'web'
        ], function () {

                /*Route::get(config('quickadmin.route') . '/users/email-credential/{id}', ['as' => 'email_credential', 'uses' => 'UsersController@emailCredential']);*/

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // Ajax routes
                Route::get('ajax-get_ad_list', ['as' => 'get_ad_list', 'uses' => 'AjaxController@AjaxGetAdsList']);

                Route::post('ajax-email', ['as' => 'ajax_email', 'uses' => 'AjaxController@AjaxEmail']);

                Route::post('ajax-country_contracts', ['as' => 'ajax_country_contracts', 'uses' => 'AjaxController@AjaxCountryContracts']);

                //Route::post('ajax-enquiry', ['as' => 'ajax_enquiry', 'uses' => 'EnquiryController@store']);

                Route::post('ajax-adstype', ['as' => 'ajax_adstype', 'uses' => 'AjaxController@AjaxAdsType']);
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

                Route::post('ajax-country', ['as' => 'ajax_country', 'uses' => 'AjaxController@AjaxCountry']);
                Route::post('ajax-countries', ['as' => 'ajax_countries', 'uses' => 'AjaxController@AjaxCountries']);

                Route::post('ajax-update', ['as' => 'ajax_update', 'uses' => 'AjaxController@AjaxUpdate']);

                // Authentication routes...
                //Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
                Route::get('login', function () {
                        return redirect(LaravelLocalization::transRoute('routes.login'), 301);
                });
                Route::post('login', ['as' => 'login_post', 'uses' => 'Auth\AuthController@postLogin']);
                Route::get(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
                Route::post(LaravelLocalization::transRoute('routes.login'), ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

                //=========================//
                // Registration routes...
                // Registration routes...
                ////Route::get('register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
                ////Route::post('register', ['as' => 'register_post', 'uses' => 'Auth\AuthController@postRegister']);
                ////Route::get(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
                ////Route::post(LaravelLocalization::transRoute('routes.register'), ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
                Route::get('register', function () {
                        return redirect(LaravelLocalization::transRoute('routes.login'), 301);
                });
                Route::get(LaravelLocalization::transRoute('routes.register'), function () {
                        return redirect(LaravelLocalization::transRoute('routes.login'), 301);
                });

                //=========================//
                // Logout routes...
                Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
                Route::get(LaravelLocalization::transRoute('routes.logout'), ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

               // Password reset link request routes...
                Route::get('password/email', ['as' => 'password_email', 'uses' => 'Auth\PasswordController@getEmail']);
                Route::get('password/email/{email}', function($email) {
                        return View::make('auth.passwords.email',['email'=>$email]);
                });
                Route::post('password/email', ['as' => 'password_email_post', 'uses' => 'Auth\PasswordController@postEmail']);

                // Password reset routes...
                Route::get('password/reset/{token}', ['as' => 'password_reset', 'uses' => 'Auth\PasswordController@getReset']);
                Route::post('password/reset', ['as' => 'password_reset_post', 'uses' => 'Auth\PasswordController@postReset']);
                Route::get('password/reset/{token}/{email}', function($token, $email) {
                        return View::make('auth.passwords.reset',['token'=>$token,'email'=>$email]);
                });
        });
});
