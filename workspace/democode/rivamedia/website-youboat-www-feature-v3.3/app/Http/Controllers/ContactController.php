<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\ContactFormRequest;
    use App\Http\Requests\ProspectiveCustomersRequest;

    use App\ContactForm;
    use App\ProspectiveCustomers;
    use Mail;

    use Illuminate\Support\Facades\Validator;

    class ContactController extends Controller
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
                $Contact = ContactForm::create($datasRequest);

                if ($Contact->save()) {
                    $datasRequest['reference'] = 'contact_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . str_slug($datasRequest['name'], '_');
                    $inputProspectiveCustomers = array(
                        'ci_last_name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                        'ci_email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'ci_phone' => !empty($datasRequest['phone']) ? $datasRequest['phone'] : null,
                        'country_code' => $country_code,
                        'referrer' => 'contact',
                        'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                    );

                    //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['email']));
                    //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                    $ProspectiveCustomers = ProspectiveCustomers::create($inputProspectiveCustomers);
                    if ($ProspectiveCustomers->save()) {
                        //$updateContactProspectiveCustomerId = ContactForm::find($Contact['id']);
                        //$updateContactProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                        //$updateContactProspectiveCustomerId->save();
                    }

                    $datasEmail = array(
                        'website_name' => config('youboat.' . $country_code . '.website_name'),
                        'type_request' => 'a contact request',
                        'name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                        'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                        'phone' => !empty($datasRequest['phone']) ? $datasRequest['phone'] : null,
                        'user_message' => !empty($datasRequest['message']) ? $datasRequest['message'] : null,
                        'country_code' => $country_code,
                        'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc'),
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

                    Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                        $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                        $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.contact') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                        $message->replyTo($datasEmail['email'], $datasEmail['name']);
                        //$message->bcc($datasEmail['bcc_mails'],$datasEmail['bcc_mails']);
                        $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                    });

                    /*$message_text = trans('emails.thanks_contact');
                    $request->session()->put('contact_message.text', $message_text);
                    $request->session()->put('contact_message.type', 'success');*/

                    $message_referrer = 'form_contact';
                    $message_title = trans('navigation.contact');
                    $message_text = trans('emails.thanks_contact');
                    $message_type = 'success';
                    $request->session()->put('contact_message.referrer', $message_referrer);
                    $request->session()->put('contact_message.title', $message_title);
                    $request->session()->put('contact_message.text', $message_text);
                    $request->session()->put('contact_message.type', $message_type);

                    $message = session()->get('contact_message');

                    //return redirect(trans_route($currentLocale, 'routes.contact'))->with('contact_message', $message_text);
                    //return redirect(trans_route($currentLocale, 'routes.contact'));
//                    return redirect(trans_route($currentLocale, 'routes.contact'))->with('contact_message', $message);
                    return redirect(trans_route($currentLocale, 'routes.contact'))->withMessage($message);
                }
            } catch(\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
                //return  redirect()->route('contact')->withErrors($e->getMessage());
                //return redirect(trans_route($currentLocale, 'routes.contact'))->withErrors($e->getMessage());
          }
        }
    }
