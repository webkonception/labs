<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\GetNotifiedRequest;
    use App\Http\Requests\ProspectiveCustomersRequest;

    use App\GetNotified;
    use App\ProspectiveCustomers;
    use Mail;

    class LandingController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //$this->middleware(['clearcache']);
        }

        /**
         * Show the application landing page.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            return view('landing.index');
        }

        /**
         * Store a newly created getnotified in storage.
         *
         * @param GetNotifiedRequest|Request $request
         */
        public function store(GetNotifiedRequest $request)
        {
            $currentLocale = config('app.locale');
            try {
                $datasRequest = $request->all();
                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
                $datasRequest['name'] = !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null;
                $getnotified = GetNotified::create($datasRequest);

                if ($getnotified->save()) {
                    $inputProspectiveCustomers = array(
                        'ci_firstname' => '',
                        'ci_last_name' => !empty($datasRequest['name']) ? $datasRequest['name'] : null,
                        'ci_email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'ci_phone' => '',
                        'country_code' => $country_code,
                        'referrer' => 'landing_notification',
                        'ci_description' => '',
                        'reference' => ''
                    );

                    //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['email']));
                    //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                    $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                    if ($ProspectiveCustomers->save()) {
                    }

                    $datasEmail = array(
                        'website_name' => config('youboat.' . $country_code . '.website_name'),
                        'name' => !empty($datasRequest['name']) ? $datasRequest['name'] : null,
                        'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'country_code' => $country_code,
                        //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                        'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                        'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                        'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                    );
                    Mail::send('emails.welcome', $datasEmail, function ($message) use ($datasEmail) {
                        $message->subject(trans('emails.welcome_to') . ' ' . $datasEmail['website_name']);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.getnotified') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        $message->to($datasEmail['email'], $datasEmail['name']);
                    });

                    /*Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                        $message->subject($datasEmail['website_name'] . " > " . trans('navigation.getnotified') . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.getnotified') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                        $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                    });*/

                    $msg = trans('emails.thanks_contact');
                    $request->session()->put('getnotified_message.text', $msg);
                    $request->session()->put('getnotified_message.type', 'success');

                    //return redirect(trans_route($currentLocale, 'routes.landing'))->with('get_notified', $msg);
                    //return redirect(trans_route($currentLocale, 'routes.landing'));
                    return redirect(trans_route($currentLocale, '/'));
                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
                //return redirect()->route('landing')->withErrors($e->getMessage());
                //return redirect(trans_route($currentLocale, 'routes.landing'))->withErrors($e->getMessage());
            }
        }
    }
