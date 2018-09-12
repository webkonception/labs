<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\ContactFormRequest;
    use App\Http\Requests\ProspectiveCustomersRequest;

    use App\ContactForm;
    use App\ProspectiveCustomers;
    use Mail;

    use Illuminate\Support\Facades\Validator;

    class LandingContactController extends Controller
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

        public function create()
        {
            return view('contact');
        }

        /**
         * Store a newly created contact in storage.
         *
         * @param ContactFormRequest|Request $request
         */
        public function store(ContactFormRequest $request)
        {
            $currentLocale = config('app.locale');
            try {
                $rules = ContactFormRequest::rules();

                $datasRequest = $request->all();
                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
                $datasRequest['name'] = !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null;
                $LandingContact = ContactForm::create($datasRequest);

                if ($LandingContact->save()) {
                    //$datasRequest['reference'] = 'landingcontact_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . mb_strtolower(studly_case($datasRequest['name']));
                    $datasRequest['reference'] = 'landingcontact_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . str_slug($datasRequest['name'], '_');
                    $inputProspectiveCustomers = array(
                        'ci_last_name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                        'ci_email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'country_code' => $country_code,
                        'referrer' => 'landing_contact',
                        'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                    );

                    //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['email']));
                    //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                    $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                    if ($ProspectiveCustomers->save()) {
                        //var_dump('$ProspectiveCustomers->save()');
                    }

                    $datasEmail = array(
                        'website_name' => config('youboat.' . $country_code . '.website_name'),
                        'type_request' => 'a landing contact request',
                        'name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                        'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'user_message' => !empty($datasRequest['user_message']) ? $datasRequest['user_message'] : null,
                        'country_code' => $country_code,
                        //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                        'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                        'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                        'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                    );
                    Mail::send('emails.contact', $datasEmail, function ($message) use ($datasEmail) {
                        $message->subject(trans('emails.welcome_to') . ' ' . $datasEmail['website_name']);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.contact') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        $message->to($datasEmail['email'], $datasEmail['name']);
                    });

                    /*Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                        $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . " from " . $datasEmail['email']);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.contact') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                        //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                        $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                    });*/

                    $msg = trans('emails.thanks_contact');
                    $request->session()->put('contact_message.text', $msg);
                    $request->session()->put('contact_message.type', 'success');

                    //return redirect(trans_route($currentLocale, 'routes.landingcontact'))->with('contact_message', $msg);
                    return redirect(trans_route($currentLocale, 'routes.landingcontact'));
                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
                //return redirect()->route('landingcontact')->withErrors($e->getMessage());
                //return redirect(trans_route($currentLocale, 'routes.landingcontact'))->withErrors($e->getMessage());
            }
        }
    }
