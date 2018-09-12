<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

use Session;
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
        $currentLocale = config('app.locale');
        app()->setLocale($currentLocale);
        $translatedRoutes = 'routes.dashboard';
        $redirectPath = trans_route($currentLocale, $translatedRoutes);
        //$redirectPath = '/';

        $this->redirectTo = $redirectPath; // after login

        $this->middleware('guest');
        $country_code = 'uk';
        if (session()->has('country_code')) {
            $country_code = session()->get('country_code');
        }
        $this->subject = config('youboat.' . $country_code . '.website_name') . ' > ' . trans('passwords.your_password_reset_link');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        try {
            return $this->sendResetLinkEmail($request);
        } catch(\Exception $e) {
            //return redirect()->back()->withErrors($e->getMessage());
            //return redirect()->back();
            return redirect('/');
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    /*protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : trans('passwords.your_password_reset_link');
    }*/

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        //$viewName = 'dashboard';
        //$currentLocale = config('app.locale');

        $message_referrer = 'form_password_email';
        $message_title = trans('navigation.auth.passwords.reset');
        $message_text = trans($response);
        $message_type = 'success';
        Session::put('message.referrer', $message_referrer);
        Session::put('message.title', $message_title);
        Session::put('message.text', $message_text);
        Session::put('message.type', $message_type);
        $message = Session::get('message');
        //return redirect()->back()->with('status', trans($response));
        //return redirect(trans_route($currentLocale, 'routes.'.$viewName));
        //return redirect(trans_route($currentLocale, 'routes.'.$viewName))->with('message', $message)->withMessage($message);
        return redirect()->back()->with('message', $message)->withMessage($message);
    }

}
