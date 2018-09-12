<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Auth;
use Mail;
use Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //protected $redirectTo = '/admin';
    protected $redirectPath = '/boatgest-admin/dashboard';
    protected $redirectTo = '/boatgest-admin/dashboard'; // url after login

    protected $username = 'username';  // you can put whatever column you want here from your users/auth table


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->subject = trans('passwords.your_password_reset_link');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            //'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $User = User::create([
            'username'     => $data['username'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => isset($data['role_id']) ? $data['role_id'] : 2, //default 2 as 'user',
            'status' => isset($data['status']) ? $data['status'] : 'inactive',
            'type'    => isset($data['type']) ? $data['type'] : 'user',
        ]);

        if ($User->save()) {
             Mail::send('emails.welcome', $data, function ($message) use ($data) {
                $message->from(env('MAIL_NO_REPLY_EMAIL'), env('APP_NAME'));
                $message->subject("Welcome to " . env('APP_NAME'));
                $message->to($data['email']);
                $message->cc(env('MAIL_USERNAME'));
            });

            $msg = 'Register confirmation email sent !';
            Session::put('register_message.text', $msg);
            Session::put('register_message.type', 'success');
        }
        return $User;
    }

    public function getLogout()
    {
        Auth::logout();
        $data['logout'] = true;
        Session::flush();
        //return redirect('/')->with('data',$data);
        $currentLocale = config('app.locale');
        //return redirect()->route('login')->with('status', trans($response));
        //return redirect(trans_route($currentLocale, 'routes.login'))->with('data',$data)->with('status', trans($response));
        return redirect(trans_route($currentLocale, 'routes.login'))->with('data',$data);
    }
}
