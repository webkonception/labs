<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\NewsletterRequest;

    use App\Newsletter;
    use App\ProspectiveCustomers;
    use Mail;

    use Illuminate\Support\Facades\Validator;

    class NewsletterController extends Controller
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

        public function create(Request $request)
        {
            if ($request) {
                $email = $request->get('email');
                return view('newsletter', compact('email'));
            } else {
                return view('newsletter');
            }
        }

        /**
         * Store a newly created newsletter in storage.
         *
         * @param NewsletterFormRequest|Request $request
         */
        //public function store(NewsletterRequest $request)
        public function store(Request $request)
        {
            $viewName       = app('request')->route()->getName();
            $currentLocale = config('app.locale');

            $datasRequest = $request->all();

            $message_referrer = !empty($datasRequest['referrer']) ? $datasRequest['referrer'] : 'form_newsletter';

            try {
                $rules = NewsletterRequest::rules();

                //$Newsletter = Newsletter::create($datasRequest);
                $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
                $datasRequest['ci_last_name'] = !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null;
                $datasRequest['email'] = !empty($datasRequest['email']) ? mb_strtolower($datasRequest['email']) : null;

                $Newsletter = Newsletter::firstOrNew(array('email' => $datasRequest['email']));
                $validator = Validator::make($datasRequest, $rules);

                if($validator->fails()) {
                    $errors = $validator->errors();
                    if($request->ajax()) {
                        $message_modal = '<ul>';
                        $message_modal .= implode('', $errors->all('<li>:message</li>'));
                        $message_modal .= '</ul>';

                        $response = [
                            'success' => false,
                            'message' => $message_modal,
                            'message_title' => '<h4 class="title strong accent-color">' . trans('footer.site-footer-top.title') .'</h4>',
                            'message_referrer' => $message_referrer,
                            'errors' => $errors
                        ];
                        $return = response()->json($response);

                        return $return;
                    } else {
                        //$datasRequest['errors'] = $errors;
                        $array['errors'] = $errors;
                        return redirect()->back()->withInput($request->input())->withErrors($errors);
                        //return view($viewName, $array)->withInput($request->input())->withErrors($errors, $this->errorBag());
                    }
                } else {
                    $Newsletter->fill($datasRequest)->save();

                    if ($Newsletter->save()) {
                        //$datasRequest['reference'] = 'nl_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . mb_strtolower(studly_case($datasRequest['name']));
                        $datasRequest['reference'] = 'nl_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . str_slug($datasRequest['name'], '_');
                        $inputProspectiveCustomers = array(
                            'ci_last_name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                            'ci_email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                            'country_code' => $country_code,
                            'referrer' => 'newsletter',
                            'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                        );

                        //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['email']));
                        //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                        $ProspectiveCustomers = ProspectiveCustomers::create($inputProspectiveCustomers);
                        if ($ProspectiveCustomers->save()) {
                            //$updateNewsletterProspectiveCustomerId = Newsletter::find($Newsletter['id']);
                            //$updateNewsletterProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                            //$updateNewsletterProspectiveCustomerId->save();
                        }

                        $datasEmail = array(
                            'website_name' => config('youboat.' . $country_code . '.website_name'),
                            'type_request' => 'a newsletter request',
                            'name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                            'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                            'country_code' => $country_code,

                            //'bcc_mails' => config('youboat.' . $request->get('country_code') . '.emails_bcc') . ',' . config('youboat.' . $request->get('country_code') . '.country_manager_email'),
                            'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                            'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                            'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                        );
                        Mail::send('emails.newsletter', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject(trans('emails.welcome_to') . ' ' . $datasEmail['website_name']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->to($datasEmail['email'], $datasEmail['name']);
                        });
                        /*Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject($datasEmail['website_name'] . " > " . $datasEmail['newsletter'] . " from " . $datasEmail['email']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                            $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                        });*/

                        /*$message_text = trans('emails.newsletter_confirmation_msg');
                        $request->session()->put('newsletter_message.text', $message_text);
                        $request->session()->put('newsletter_message.type', 'success');*/

                        if($request->ajax()) {
                            $message_text = trans('emails.newsletter_confirmation_msg');

                            /*$message_text .= '<script>' . "\n";
                            $message_text .= '  $("#msgModalAjax_' . $message_referrer . '").on("hidden.bs.modal", function (e) {' . "\n";
                            $message_text .= '        $("#' . $message_referrer . '").html(\'<p class="alert alert-success text-success">' . trans('emails.newsletter_confirmation_msg') . '</p>\');' . "\n";
                            $message_text .= '  });' . "\n";
                            $message_text .= '</script>' . "\n";*/
                            $response = [
                                'success' => true,
                                'message' => $message_text,
                                'message_title' => '<h4 class="title strong accent-color">' . trans('footer.site-footer-top.title') .'</h4>',
                                'success_message' => trans('emails.enquiry_confirmation_msg'),
                                'message_referrer' => $message_referrer
                            ];
                            $return = response()->json($response);
                            return $return;
                        } else {
                            //$message_referrer = 'form_newsletter';
                            $message_title = trans('navigation.newsletter');
                            $message_text = trans('emails.newsletter_confirmation_msg');
                            $message_type = 'success';
                            $request->session()->put('newsletter_message.referrer', $message_referrer);
                            $request->session()->put('newsletter_message.title', $message_title);
                            $request->session()->put('newsletter_message.text', $message_text);
                            $request->session()->put('newsletter_message.type', $message_type);

                            $message = session()->get('newsletter_message');

                            //return view($viewName, ['newsletter_message', $message])->withInput($request->input())->with('newsletter_message', $message);
                            /*return redirect()
                                ->back()
                                //->withInput($request->input())
                                ->with('newsletter_message', $message);*/

                            //return redirect(trans_route($currentLocale, 'routes.newsletter'))->with('newsletter_message', $message);
                            return redirect(trans_route($currentLocale, 'routes.newsletter'))->withMessage($message);
                        }
                    }
                }
            } catch(\Exception $e) {
                $error_message = $e->getMessage();

                if($request->ajax()) {
                    // prevent GMAIL Timeout
                    // Connection to ssl://smtp.gmail.com:465 Timed Out
                    if (preg_match('/Connection to ssl/', $error_message) || preg_match('/Timed Out/', $error_message)) {
                        $error_message = trans('errors/503.error_message');
                        $error_message .= '<script>' . "\n";
                        $error_message .= '    $("#msgModalAjax_' . $message_referrer . '").delay(5000).queue(function() {' . "\n";
                        $error_message .= '      $("#msgModalAjax_' . $message_referrer . '").modal("hide");' . "\n";
                        $error_message .= '      $(this).dequeue();' . "\n";
                        $error_message .= '    });' . "\n";
                        $error_message .= '</script>' . "\n";
                    }
                    /*$response = [
                        'success' => false,
                        'message' => $error_message,
                        'message_referrer' => $message_referrer
                    ];*/
                    $response = [
                        'success' => false,
                        'message_title' => '<h4 class="title strong accent-color">' . trans('footer.site-footer-top.title') .'</h4>',
                        'message' => $error_message,
                        'message_referrer' => $message_referrer,
                        'errors' => $errors
                    ];
                    $return = response()->json($response);
                    return $return;
                } else {
                    //return redirect()->back()->withErrors($e->getMessage());
                    return redirect(trans_route($currentLocale, 'routes.newsletter'))->withErrors($e->getMessage());
                    //return  redirect()->route('newsletter')->withErrors($e->getMessage());
                    //return redirect(trans_route($currentLocale, 'routes.newsletter'))->withErrors($e->getMessage());
                }
            }
        }
    }
