<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Role;
use App\User;
use App\CustomersCaracts;
use App\CommercialsCaracts;
use App\DealersCaracts;
use App\PrivatesCaracts;

use Auth;
use Session;
use Mail;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUsersRequest;

use Illuminate\Support\Facades\Hash;
use QueryException;

use Illuminate\Support\Facades\Validator;

class UsersController extends BoatgestAdminController
{
    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //$users = User::all();
        $users = User::orderBy('updated_at', 'desc')->get();
        $roles = Role::orderBy('title', 'asc')->lists('title', 'id');
        return view(config('quickadmin.route') . '.users.index', compact('users', 'roles'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $roles = Role::orderBy('title', 'asc')->lists('title', 'id');
        $types = getEnumValues('users', 'type');
        $status = getEnumValues('users', 'status');

        return view(config('quickadmin.route') . '.users.create', compact('roles', 'types', 'status', 'input'));
    }

    /**
     * Insert new user into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUsersRequest $request)
    //public function store(Request $request)
    {
        /*$input = $request->all();
        $input['status'] = (isset($input['status']) && '' !== $input['status']) ? $input['status'] : 'inactive';
        $input['password'] = bcrypt($input['password']);
        //$input['password'] = Hash::make($input['password']);
        //$user = User::create($input);

        try {
            $user = User::create($input);

            if ($user->save()) {
                $message = 'User was successfully created!';
                Session::set('message.text',$message); //Session::flash
                Session::set('message.type', 'success'); //Session::flash
                return redirect()->route('users.index')->withMessage($message);
            }
        } catch(\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }*/
        try {
            $datasRequest = $request->all();
            $datasRequest['status'] = (isset($datasRequest['status']) && '' !== $datasRequest['status']) ? $datasRequest['status'] : 'inactive';
            $datasRequest['password'] = bcrypt($datasRequest['password']);

            $rulesUsers = CreateUsersRequest::rules();
            $validator = Validator::make($datasRequest, $rulesUsers);
            if($validator->fails()) {
                $errors = $validator->errors();
                $array = $datasRequest;
                $array['errors'] = $errors;

                $return = $array;
                //redirect()->route('users.index')->withInput($request->input())->withErrors($errors, $this->errorBag());
                redirect()->back()->withInput($request->input())->withErrors($errors, $this->errorBag());
            } else {
                $Users = User::Create($datasRequest);
                //$Users = Users::firstOrNew($datasRequest);
                if ($Users->save()) {
                    $message = 'User was successfully created!';
                    Session::set('message.text',$message); //Session::flash
                    Session::set('message.type', 'success'); //Session::flash
                    return redirect()->route('users.index')->withMessage($message);
                }
            }
        } catch(\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }

        //return redirect()->route('users.index')->withMessage('User was successfully created!');
    }

    /**
     * Show a user edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $user_type = $user->type;
        $user_id = $user->id;
        $caracts_need_to_create = false;
        $caracts_id = '';
        //SELECT id FROM `customerscaracts` WHERE user_id = 460
        $caracts = '';
        switch($user_type) {
            case 'private':
                $caracts = PrivatesCaracts::where('user_id', $user_id)->lists('id');
                break;
            case 'dealer':
                $caracts = DealersCaracts::where('user_id', $user_id)->lists('id');
                break;
            case 'commercial':
                $caracts = CommercialsCaracts::where('user_id', $user_id)->lists('id');
                break;
            case 'customer':
                $caracts = CustomersCaracts::where('user_id', $user_id)->lists('id');
                break;
        }
        if(isset($caracts)) {
            $array = json_decode(json_encode($caracts), true);
            if (is_array($array) && !empty($array)) {
                $caracts_id = $array[0];
            }
            if(empty($caracts_id)) {
                $caracts_need_to_create = true;
            }
        }
        $roles = Role::orderBy('title', 'asc')->lists('title', 'id');
        $types = getEnumValues('users', 'type');
        $status = getEnumValues('users', 'status');

        return view(config('quickadmin.route') . '.users.edit', compact('caracts_id', 'user', 'roles', 'types', 'status', 'user_type','caracts_need_to_create'));
    }

    /**
     * Update our user information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->all();
        if (Hash::needsRehash($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
        $user->update($input);

        return redirect()->route('users.index')->withMessage('User was successfully updated!');
    }

    /**
     * logout and reset password
     *
     * @param         $email
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutResetPassword($email)
    {
        Auth::logout();
        return view('auth.passwords.email', ['email'=>$email]);
    }

    /**
     * UpdatePassword
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $return = [];

            if(Auth::check()) {
                $input = $request->all();

                $user_id = Auth::user()->id;
                $user_email = Auth::user()->email;
                $user_username = Auth::user()->username;
                $user_type = Auth::user()->type;

                if('admin' == $user_type || 'commercial' == $user_type) {
                    $result = User::where('email', $input['email'])
                        ->select('id','username')
                        ->get();
                    $user = json_decode(json_encode($result), true)[0];
                    $user_id = $user['id'];
                    $user_username = $user['username'];
                }

                if($user_email == $input['email'] || 'admin' == $user_type || 'commercial' == $user_type) {
                //if($user_email == $input['email']) {
                    $input['username'] = $user_username;
                    //$user = User::findOrFail($user_id);
                    $rulesUser = [
                        'username' => 'required|max:255',
                        'email'    => 'required|email|max:255',
                        'password' => 'required|confirmed|min:6',
                    ];

                    $request = new Request($input);
                    $datasRequest = $request->all();

                    $validator = Validator::make($datasRequest, $rulesUser);
                    if($validator->fails()) {
                        $errors = $validator->errors();
                        return redirect()->back()->with($request->input())->withErrors($errors, $this->errorBag());
                    } else {
                        $currentLocale = config('app.locale');

                        $user = User::findOrFail($user_id);

                        if($updatePassword = $user->fill([
                            'password' => Hash::make($request->password)
                        ])->save()) {
                            return redirect()->route(config('quickadmin.route') .'.dashboard.index')->withMessage('Password for &laquo;&nbsp;' . $user_username . '&nbsp;&raquo; was successfully updated!');
                        }
                    }
                } else {
                    return redirect()->back()->withErrors($user_email .' =/= '. $input['email']);
                }

            }
        } catch(\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * generatePassword
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generatePassword($usernames = [])
    {
        $passwords = [];
        $dt = \Carbon\Carbon::now();
        if (is_array($usernames) && !empty($usernames)) {
            foreach ($usernames as $username) {
                $passwords += [
                    $username => generateStrongPassword(8, false, 'lud', [ $username. '_' . $dt->year])
                ];
            }
        }
        return $passwords;
    }

    /**
     * emailCredential
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailCredential(Request $request, $id = '', $passwords = [])
    {
        try {
            $message = '';

            /*$passwords = [
                'login' => 'password'
            ];*/

            /*$passwords += [
                'mgmboats' => 'WERC13dj',
                'bic_aluminium' => 'UUjn4cAe',
                'prestonmarina' => 'mJ7JUar9',
                'essexparkandlaunch' => 'ygGaeq94',
                'braymarinesales' => 'waXYmN7w',
                'inspirationmarine' => 'An94aekD',
                'hainesmarine' => 'nyBam3Sr',
                'scotlandnautique' => 'zug3Q0fR',
                'fairline' => 'sZsal70e',
                'marinechartersolutions' => 'zJ7Dp2vr',
                'shetlandboats' => 'Eux4W7sX',
                'sacsmarine' => 'a8bU8Ah2',
                'galeonyachts' => 'XM9ks1h3',
                'birchellmarine' => 'KcEecsD7',
                'yamaha_motor' => 'PMNmjou3',
                'suzuki' => '7Rv3uSDC',
                'sbstrailers' => 'Z10h6wb5',
                '68_marine_solutions' => '23eU7fUo',
                'aquaticboatcenters' => 'xc0qQ4jQ',
                'boatingmania' => 'aau6gMgE',
                'excel_boats' => '3b2YenAP',
                'garmin' => 'vmGgr45g',
                'tuimarine' => '2n8mRwv1',
            ];*/
            $input = $request->all();
            $country_code = !empty($input['country_code']) ? $input['country_code'] : 'uk';
            $date = $input['date'];
            $result = User::where('updated_at', 'LIKE', "%$date%")
                ->where('notified', '=', 0)
                ->whereNull('created_at')
                ->select('id', 'type', 'username', 'email')
                ->orderBy('username', 'ASC')
                ->get();
            $users = json_decode(json_encode($result), true);
            if (is_array($passwords) && !empty($passwords)) {
                foreach ($users as $k => $user) {
                    $user_login = $user['username'];
                    $user_type = $user['type'];
                    $user_id = $user['id'];
                    $user_email = $user['email'];
                    $input["id"] = $user_id;
                    $input["username"] = $user_login;
                    $input['password'] = array_key_exists($user_login, $passwords) ? $passwords[$user_login] : '';
                    $input["email"] = $user_email;
                    $user_caracts = [];
                    switch ($user_type) {
                        case 'admin':
                            $usercaracts = [];
                            break;
                        case 'private':
                            $usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
                            break;
                        case 'dealer':
                            $usercaracts = DealersCaracts::where('user_id', $user_id)->get();
                            break;
                        case 'customer':
                            $usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
                            break;
                        case 'commercial':
                            $usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
                            break;
                    }
                    $array = json_decode(json_encode($usercaracts), true);
                    if (!empty($array[0])) {
                        $user_caracts = $array[0];
                    }
                    if (is_array($user_caracts) && array_key_exists('id', $user_caracts)) {
                        $dealerscaracts_id = $user_caracts['id'];
                        $ad_dealer_name = isset($user_caracts['denomination']) ? $user_caracts['denomination'] : '';
                        if (empty($ad_dealer_name) && (isset($user_caracts['firstname']) ||  isset($user_caracts['name']))) {
                            $ad_dealer_name = $user_caracts['name'] . (!empty($user_caracts['firstname']) ? ' ' . $user_caracts['firstname'] : '');
                        }
                        $ad_dealer_name = trim(ucwords(mb_strtolower($ad_dealer_name)));

                        $input["dealerscaracts_id"] = $dealerscaracts_id;
                        $input["name"] = $ad_dealer_name;
                    }
                    if (app()->isLocal()) {
                        $input["email"] = 'edeiller+pwd@gmail.com';
                    }
                    $datasEmail = array(
                        'website_name' => config('youboat.' . $country_code . '.website_name') . ' YOUBOAT',
                        'website_url' => config('youboat.' . $country_code . '.website_youboat_url'),
                        'name' => $input["name"],
                        'email' => $input["email"],
                        'username' => $input["username"],
                        'password' => $input["password"],
                        'country_code' => $country_code,
                        'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                        'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                        'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                    );

                    Mail::send('emails.credential', $datasEmail, function ($message) use ($datasEmail) {
                        $subject = trans('emails.welcome_to') . ' ' . $datasEmail['website_name'] . ', ' . trans('emails.your_credentials');
                        $message->subject($subject);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        $message->to($datasEmail['email'], $datasEmail['name']);
                    });

                    if (count(Mail::failures()) > 0) {

                        $message .= 'Error:[';
                        foreach (Mail::failures as $email_address) {
                            $message .= "$email_address;";
                        }
                        $message .= ']';

                    } else {
                        $message .= '' . $input["email"] . ';';
                        if (Hash::needsRehash($input['password'])) {
                            $input['password'] = Hash::make($input['password']);
                        }
                        $user = User::where('username', $input["username"])->update([
                            //'username' => $input["username"],
                            //'password' => $input['password'],
                            'notified' => 1
                        ]);
                    }
                }
            }
            return redirect()->route('users.index')->withMessage($message);
        } catch(\Exception $e) {
            var_dump($e->getMessage());
            die();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * emailCredential
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function genUserPwd(Request $request, $id = '', $usernames = [])
    {
        try {
            $message = '';
            $usernames += [
                'mgmboats',
                'bic_aluminium',
                'prestonmarina',
                'essexparkandlaunch',
                'braymarinesales',
                'inspirationmarine',
                'hainesmarine',
                'scotlandnautique',
                'fairline',
                'marinechartersolutions',
                'shetlandboats',
                'sacsmarine',
                'galeonyachts',
                'birchellmarine',
                'yamaha_motor',
                'suzuki',
                'sbstrailers',
                '68_marine_solutions',
                'aquaticboatcenters',
                'boatingmania',
                'excel_boats',
                'garmin',
                'tuimarine'
            ];
            $passwords = $this->generatePassword($usernames);

            foreach ($passwords as $username => $password) {
                echo "'" . $username . "' => '" . $password . "',<br>";
            }
            die();
        } catch(\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    /**
     * Destroy specific user
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::destroy($id);

        return redirect()->route('users.index')->withMessage('User was successfully deleted!');
    }
}