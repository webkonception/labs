<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    //protected $redirectTo = '/login';
    //protected $redirectPath = '/login';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = '/boatgest-admin';
        $this->middleware('guest');
        $country_code = 'uk';
        if (session()->has('country_code')) {
            $country_code = session()->get('country_code');
        }
        $this->subject = config('youboat.' . $country_code . '.website_name') . ' Youboat ' . mb_strtoupper($country_code) . ' > ' . trans('passwords.your_password_reset_link');
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        $password_reset_txt = trans('passwords.password_reset_txt');
        //return redirect()->route('login')->with('status', trans($response) . '<br>' . $password_reset_txt);
        //return redirect()->route(app('laravellocalization')->transRoute('routes.login'))->with('status', trans($response) . '<br>' . $password_reset_txt);
        $currentLocale = config('app.locale');
        return redirect(trans_route($currentLocale, 'routes.login'))->with('status', trans($response) . '<br>' . $password_reset_txt);
    }
}
