<?php
namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
//use App\Http\Requests\ProspectiveCustomersRequest;

use App\ProspectiveCustomers;
use Mail;
use Cache;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    //protected $redirectTo = 'welcome';
    protected $username = 'email';  // you can put whatever column you want here from your users/auth table

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $currentLocale = config('app.locale');
        //app()->setLocale($currentLocale);
        $translatedRoutes = 'routes.dashboard';
        $redirectPath = trans_route($currentLocale, $translatedRoutes);
        $this->redirectPath = $redirectPath; // after login

        $redirectAfterLogout = trans_route($currentLocale, 'routes.login'); // after logout
        //$redirectAfterLogout = '/'; // after logout
        $this->redirectAfterLogout = $redirectAfterLogout;

        //$this->middleware('guest', ['except' => 'getLogout']);
        //$this->middleware('guest', ['except' => ['password_email','logout', 'getLogout']]);
        $this->middleware('guest', ['except' => ['getLogout']]);
        $this->subject = trans('passwords.your_password_reset_link');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => 'required|max:255',
            'username'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            //'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $datasRequest)
    {
        $currentLocale = config('app.locale');
        $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
        /*return User::create([
                            'name' => $datasRequest['name'],
                            'email' => $datasRequest['email'],
                            'password' => bcrypt($datasRequest['password']),
                        ]);*/

        $User = User::create([
            'username'     => $datasRequest['username'],
            'email'    => $datasRequest['email'],
            'password' => bcrypt($datasRequest['password']),
            'role_id' => isset($datasRequest['role_id']) ? $datasRequest['role_id'] : 2, //default 2 as 'user',
            'status' => isset($datasRequest['status']) ? $datasRequest['status'] : 'inactive',
            'type'    => isset($datasRequest['type']) ? $datasRequest['type'] : 'user',
        ]);

        if ($User->save()) {
            $datasRequest['reference']      = 'registration_' . $country_code . '_' . str_slug($datasRequest['username'], '_');
            $inputProspectiveCustomers = array(
                'ci_last_name' => !empty($datasRequest['username']) ? $datasRequest['username'] : null,
                'ci_email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                'country_code' => $country_code,
                'referrer' => 'user_registration',
                'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
            );
            //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
            //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
            $ProspectiveCustomers = ProspectiveCustomers::create($inputProspectiveCustomers);
            if ($ProspectiveCustomers->save()) {
                //$updateUserProspectiveCustomerId = User::find($User['id']);
                //$updateUserProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                //$updateUserProspectiveCustomerId->save();
            }

            $datasEmail = array(
                'website_name' => config('youboat.' . $country_code . '.website_name'),
                'type_request' => 'an user registration request',
                'name' => !empty($datasRequest['username']) ? $datasRequest['username'] : null,
                'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
            );
            Mail::send('emails.welcome', $datasEmail, function ($message) use ($datasEmail) {
                $message->subject(trans('emails.welcome_to') . ' ' . $datasEmail['website_name']);
                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.register') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                $message->to($datasEmail['email'], $datasEmail['name']);
            });

            Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                $message->subject($datasEmail['website_name'] . " > " . "Request " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.register') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                //$message->to(env('MAIL_CONTACT_EMAIL'), env('MAIL_CONTACT_NAME') . ' ' . $datasEmail['website_name']);
                //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
            });

            $msg = trans('emails.register_confirmation_msg');
            Session::put('register_message.text', $msg);
            Session::put('register_message.type', 'success');
            //Session::put('message.text', $msg);
            //Session::put('message.type', 'success');
        }
        return $User;
    }
    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $currentLocale = config('app.locale');
        //app()->setLocale($currentLocale);
        //app('laravellocalization')->setLocale($currentLocale);

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        Auth::guard($this->getGuard())->login($this->create($request->all()));
        return redirect($this->redirectPath());
    }

    /*
    public function getLogout()
    {
        Auth::logout();
        $data['logout'] = true;
        Session::flush();
        Cache::flush();

        //return redirect('/')->with('data',$data);
        $currentLocale = config('app.locale');
        //return redirect()->route('login')->with('status', trans($response));
        return redirect(trans_route($currentLocale, 'routes.login'))->with('data',$data);
    }
    */
}
