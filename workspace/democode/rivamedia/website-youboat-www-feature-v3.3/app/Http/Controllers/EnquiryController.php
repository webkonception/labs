<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Requests\EnquiryFormRequest as EnquiryFormRequest;
    use App\Http\Requests\BodFormRequest as BodFormRequest;
    //use App\Http\Requests\ProspectiveCustomersRequest;

    use App\User;
    use App\EnquiryForm;
    use App\BodCaracts;
    use App\CustomersCaracts;
    use App\ProspectiveCustomers;

    use Mail;
    use Form;
    use Auth;
    use Session;

    use Illuminate\Support\Facades\Validator;

    class EnquiryController extends ForsaleController
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
         * Show the application dashboard.
         *
         * @return \Illuminate\Http\Response
         */
        public function create (Request $request)
        {
            $viewName       = app('request')->route()->getName();
            if ($request) {
                $datasRequest = $request->all();
                return view($viewName, $this->getDefaults($datasRequest))->withInput($request->all());
            } else {
                return view($viewName, $this->getDefaults([]));
            }
        }

        /**
         * Store a newly created enquiry in storage.
         *
         * @param Request|Request $request
         */
        //public function store(EnquiryFormRequest $request)
        public function store(Request $request)
        {
            try {
                if($request->ajax()) {
                    $rules = EnquiryFormRequest::rules();

                    $datasRequest = $request->all();

                    $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';

                    //==========
                    $datasRequest['ad_id'] =!empty($datasRequest['ad_id']) ? $datasRequest['ad_id'] : '';
                    $datasRequest['ad_url'] = !empty($datasRequest['ad_url']) ? $datasRequest['ad_url'] : '';
                    $datasRequest['ad_title'] = !empty($datasRequest['ad_title']) ? $datasRequest['ad_title'] : '';
                    $ad_title = !empty($datasRequest['ad_title']) ? $datasRequest['ad_title'] . (!empty($datasRequest['ad_budget']) ? ' (' . $datasRequest['ad_budget'] . ')' : '') : '';

                    $datasRequest['ad_type'] = !empty($datasRequest['ad_type']) ? $datasRequest['ad_type'] : '';
                    $datasRequest['adstypes_id'] = !empty($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : '';
                    $datasRequest['ad_category'] = !empty($datasRequest['ad_category']) ? $datasRequest['ad_category'] : '';
                    $datasRequest['categories_ids'] = !empty($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : '';
                    $datasRequest['ad_subcategory'] = !empty($datasRequest['ad_subcategory']) ? $datasRequest['ad_subcategory'] : '';
                    $datasRequest['subcategories_ids'] = !empty($datasRequest['subcategories_ids']) ? $datasRequest['subcategories_ids'] : '';
                    $datasRequest['ad_manufacturer'] = !empty($datasRequest['ad_manufacturer']) ? $datasRequest['ad_manufacturer'] : '';
                    $datasRequest['manufacturers_id'] = !empty($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : '';
                    $datasRequest['ad_model'] = !empty($datasRequest['ad_model']) ? $datasRequest['ad_model'] : '';
                    $datasRequest['models_id'] =!empty($datasRequest['models_id']) ? $datasRequest['models_id'] : '';

                    $datasRequest['ci_firstname'] = !empty($datasRequest['ci_firstname']) ? ucfirst(mb_strtolower($datasRequest['ci_firstname'])) : '';
                    $datasRequest['ci_last_name'] = !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : '';
                    $datasRequest['ci_email'] = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : '';
                    $datasRequest['ci_phone'] = !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : '';
                    $datasRequest['budget'] = !empty($datasRequest['budget']) ? $datasRequest['budget'] : '';
                    //$datasRequest['currency'] = !empty($datasRequest['currency']) ? $datasRequest['currency'] : '';
                    $datasRequest['ci_countries_id'] = !empty($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : '';

                    //==========

                    //$datasRequest['reference'] = 'enquiry_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . str_slug($datasRequest['ci_last_name'], '_') . '_' . $datasRequest['ad_id'];
                    $datasRequest['reference'] = 'enquiry_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . (!empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? str_slug(mb_strtolower($datasRequest['ci_firstname'])[0] . mb_strtolower($datasRequest['ci_last_name']), '_') : str_slug(mb_strtolower($datasRequest['ci_last_name']), '_') : '') . '_' . $datasRequest['ad_id'];

                    // @TODO : manage unique reference
                    //$datasRequest['reference'] = 'enquiry_' . $country_code . '_' . date("Ymd") . '_' . str_slug($datasRequest['ci_last_name'], '_') . '_' . $datasRequest['ad_id'];

                    $product = '';
                    $product .= empty($product) && !empty($datasRequest['ad_manufacturer']) ? $datasRequest['ad_manufacturer'] : '';
                    $product .= !empty($product) && !empty($datasRequest['ad_model']) ? ' ' . $datasRequest['ad_model'] : '';
                    if(empty($product)) {
                        if(!empty($datasRequest['ad_title'])) {
                            $product = $datasRequest['ad_title'];
                        }
                    }

                    $Enquiry = EnquiryForm::firstOrNew(array('reference' => $datasRequest['reference']));
                    $validator = Validator::make($datasRequest, $rules);

                    if($validator->fails()) {
                        $errors = $validator->errors();
                        /*$response = [
                            'success' => false,
                            'message' => $errors
                        ];*/
                        $message_modal = '<ul>';
                        $message_modal .= implode('', $errors->all('<li>:message</li>'));
                        $message_modal .= '</ul>';
                        $response = [
                            'success' => false,
                            'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                            'message' => $message_modal,
                            'message_referrer' => 'form_enquiry',
                            'errors' => $errors
                        ];
                        $return = response()->json($response);
                        return $return;
                    } else {
                        $Enquiry->fill($datasRequest)->save();

                        if ($Enquiry->save()) {
                            $inputProspectiveCustomers = array(
                                'ci_firstname' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                'ci_last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                'ci_email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                'ci_phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                'country_code' => $country_code,
                                'referrer' => 'enquiry',
                                'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                            );

                            //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                            //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                            $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                            if ($ProspectiveCustomers->save()) {
                                $updateEnquiryProspectiveCustomerId = EnquiryForm::find($Enquiry['id']);
                                $updateEnquiryProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                                $updateEnquiryProspectiveCustomerId->save();
                            }

                            $details = [];
                            if(!empty($datasRequest)) {
                                $details = array(
                                    'url' => link_to($datasRequest['ad_url'], $ad_title, []),
                                    //'first_name' => $datasRequest['ci_firstname'],
                                    //'last_name' => $datasRequest['ci_last_name'],
                                    'email' => $datasRequest['ci_email'],
                                    'phone' => $datasRequest['ci_phone'],
                                    'description' => $datasRequest['ci_description']
                                );
                            }
                            $customer_name = !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] . ' ' . $datasRequest['ci_last_name'] : $datasRequest['ci_last_name'] : '';

                            $dealerscaracts_id = SearchController::getSomethingById('gateway_ads_details', $datasRequest['ad_id'], 'dealerscaracts_id');
                            $dealer_id = SearchController::getSomethingById('dealerscaracts', $dealerscaracts_id, 'user_id');
                            $dealer_denomination = SearchController::getSomethingById('dealerscaracts', $dealerscaracts_id, 'denomination');
                            $dealer_email = SearchController::getSomethingById('users', $dealer_id, 'email');

                            $dealer_name = !empty($dealer_denomination[0]) && array_key_exists('denomination', $dealer_denomination[0]) && !empty($dealer_denomination[0]['denomination']) ? $dealer_denomination[0]['denomination'] : config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME');
                            if(app()->isLocal()) {
                                $dealer_email = 'emmanuel.deiller.rivamedia+dealer_test@gmail.com';
                            } else {
                                $dealer_email = !empty($dealer_email[0]) && array_key_exists('email', $dealer_email[0]) && !empty($dealer_email[0]['email']) ? $dealer_email[0]['email'] : config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL');
                            }

                            $datasEmail = array(
                                //'reference' => $datasRequest['reference'],
                                'ad_url' => $datasRequest['ad_url'],
                                'ad_title' => $ad_title,
                                'ad_budget' => $datasRequest['ad_budget'],
                                'details' => $details,
                                'website_name' => config('youboat.' . $country_code . '.website_name'),
                                'type_request' => 'a request about "' . $ad_title . '"',
                                'name' => $customer_name,
                                'email' => $datasRequest['ci_email'],
                                'country_code' => $country_code,
                                //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                                'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                                'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                                'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                                'dealer_name' => $dealer_name,
                                'dealer_email' => $dealer_email,
                            );

                            // email to the dealer
                            Mail::send('emails.enquiry_notify', $datasEmail, function ($message) use ($datasEmail) {
                                $subject = $datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email'];
                                $message->subject($subject);

                                $from_email = $datasEmail['MAIL_NO_REPLY_EMAIL'];
                                $from_name = $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name'];
                                $message->from($from_email, $from_name);

                                $reply_email = $datasEmail['email'];
                                $reply_name = $datasEmail['name'];
                                $message->replyTo($reply_email, $reply_name);

                                $to_email = $datasEmail['dealer_email'];
                                $to_name = $datasEmail['dealer_name'];
                                $message->to($to_email, $to_name);

                                //$message->sender($reply_email, $reply_name);
                            });
                            // Disable email to the customer
                            /*
                            Mail::send('emails.enquiry', $datasEmail, function ($message) use ($datasEmail) {
                                $message->subject(trans('emails.enquiry_confirmation_msg') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('emails.your_enquiry_of_informations_for_an_ad') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                $message->to($datasEmail['email'], $datasEmail['name']);
                            });*/

                            $datasEmail['reference'] = $datasRequest['reference'];
                            Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                                $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                                $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                            });

                            $form = Form::open(array('url'=>'/ajax-bod', 'class'=>'well well-white form-horizontal ajax-form', 'role'=>'form', 'id'=>'form_bod', 'autocomplete'=>'off')) . "\n";
                            $form .= Form::hidden('ad_id', $datasRequest['ad_id']) . "\n";
                            $form .= Form::hidden('ad_url', $datasRequest['ad_url']) . "\n";
                            $form .= Form::hidden('ad_title', $datasRequest['ad_title']) . "\n";
                            //$form .= Form::hidden('currency', $datasRequest['currency']) . "\n";
                            //$form .= Form::hidden('ad_budget', $datasRequest['ad_budget']) . "\n";
                            //$form .= Form::hidden('ad_type', $datasRequest['ad_type']) . "\n";
                            $form .= Form::hidden('adstypes_id', $datasRequest['adstypes_id']) . "\n";
                            //$form .= Form::hidden('ad_category', $datasRequest['ad_category']) . "\n";
                            $form .= Form::hidden('categories_ids', $datasRequest['categories_ids']) . "\n";
                            //$form .= Form::hidden('ad_subcategory', $datasRequest['ad_subcategory']) . "\n";
                            $form .= Form::hidden('subcategories_ids', $datasRequest['subcategories_ids']) . "\n";
                            //$form .= Form::hidden('ad_manufacturer', $datasRequest['ad_manufacturer']) . "\n";
                            $form .= Form::hidden('manufacturers_id', $datasRequest['manufacturers_id']) . "\n";
                            //$form .= Form::hidden('ad_model', $datasRequest['ad_model'] : '') . "\n";
                            $form .= Form::hidden('models_id', $datasRequest['models_id']) . "\n";
                            $form .= Form::hidden('sell_type', $datasRequest['sell_type']) . "\n";

                            $form .= Form::hidden('ci_firstname', $datasRequest['ci_firstname']) . "\n";
                            $form .= Form::hidden('ci_last_name', $datasRequest['ci_last_name']) . "\n";
                            $form .= Form::hidden('ci_email', $datasRequest['ci_email']) . "\n";
                            $form .= Form::hidden('ci_description', $datasRequest['ci_description']) . "\n";
                            $form .= Form::hidden('country_code', $country_code) . "\n";

                            // BUDGET
                            $label_txt = ucfirst(trans('filters.your')) . ' ' . trans('boat_on_demand.budget') . ' *';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                'required'=>'required',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control',
                                'id' => 'budget'
                            ];
                            $css_state = '';
                            if (!empty($datasRequest['budget'])) {
                                $css_state = 'has-success';
                            }
                            $attributes['required'] = 'required';

                            $form .= '<div class="form-group ' . $css_state . '">' . "\n";
                            $form .= '    ' . Form::label('budget', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) . "\n";
                            $form .= '    <div class="col-xs-12 col-sm-7">' . "\n";
                            $form .= '        <div class="input-group">' . "\n";
                            $form .= '            ' . Form::text('budget', !empty($datasRequest['budget']) ? $datasRequest['budget'] : old('budget'), $attributes) . "\n";
                            $form .= '            <span class="input-group-addon">' . config('youboat.'. $country_code .'.currency') . '</span>' . "\n";
                            $form .= '        </div>' . "\n";
                            //$form .= '            ' . Form::text('budget', !empty($datasRequest['ad_budget']) ? $datasRequest['ad_budget'] : old('ad_budget'), $attributes) . "\n";
                            $form .= '    </div>' . "\n";
                            $form .= '</div>' . "\n";
                            // /BUDGET

                            // PHONE
                            $label_txt = ucfirst(trans('filters.your')) . ' ' . trans('validation.attributes.phone') . ' *';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $attributes = [
                                'required'=>'required',
                                'data-placeholder' => $placeholder,
                                'placeholder' => $placeholder,
                                'class' => 'form-control', 'id' => 'ci_phone'
                            ];
                            $css_state = '';
                            if (!empty($datasRequest['ci_phone'])) {
                                $css_state = 'has-success';
                            }
                            $form .= '<div class="form-group ' . $css_state . '">' . "\n";
                            $form .= '    ' . Form::label('ci_phone', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) . "\n";
                            $form .= '    <div class="col-xs-12 col-sm-7">' . "\n";
                            $form .= '        <div class="input-group">' . "\n";
                            $form .= '          ' .  Form::tel('ci_phone', isset($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : old('ci_phone'), $attributes) . "\n";
                            $form .= '        </div>' . "\n";
                            $form .= '    </div>' . "\n";
                            $form .= '</div>' . "\n";
                            // /PHONE

                            // COUNTRY
                            $countries = SearchController::getCountries();

                            if (isset($countries)) {
                                $label_txt = ucfirst(trans('filters.your')) . ' ' . trans('validation.attributes.country') . ' *';
                                $placeholder = trans('navigation.form_select_placeholder');
                                $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'required'=>'required',
                                    'class' => 'form-control',
                                    'id' => 'ci_countries_id'
                                ];
                                if (!count($countries) > 0) {
                                    $attributes['disabled'] = 'disabled';
                                }
                                $css_state = '';
                                if (!empty($datasRequest['ci_countries_id']) || count($countries) === 1) {
                                    $css_state = 'has-success';
                                }
                                $form .= '<div class="form-group ' .  $css_state  . '">' . "\n";
                                $form .= '    ' . Form::label('ci_countries_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) . "\n";
                                $form .= '    <div class="col-xs-12 col-sm-7">' . "\n";
                                $form .= '        <div class="input-group">' . "\n";
                                            if (count($countries) === 1) {
                                                $array = json_decode(json_encode($countries), true);
                                                $key = key($array);
                                                $form .= Form::text('ci_country_val', $countries->first(), $attributes);
                                                $form .= Form::hidden('ci_countries_id', $key);
                                            } else {
                                                $form .= Form::select('ci_countries_id', $countries, isset($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : old('ci_countries_id'), $attributes);
                                            }
                                $form .= '        </div>' . "\n";
                                $form .= '    </div>' . "\n";
                                $form .= '</div>' . "\n";
                            }
                            // /COUNTRY

                            $form .= '<div class="row">' . "\n";
                            $form .= '    <div class="col-xs-12 col-sm-offset-5 col-sm-7">' . "\n";
                            $form .= '        ' . Form::button( '<i class="pull-right fa fa-check-circle fa-3x fa-fw"></i>' . '<span class="big">' . mb_strtolower(trans('navigation.confirm')) . '</span>', ['type' => 'submit', 'data-ga'=>'transfo-bod~' . trans('navigation.send_enquiry') . '|' . 'Ref. ' . $datasRequest['ad_url'], 'class' => 'GA_event btn btn-block btn-md btn-success btn-exception']);
                            $form .= '    </div>' . "\n";
                            $form .= '</div>' . "\n";
                            $form .= Form::close();

                            $message_text = '<p class="text-center text-primary"><small><em>' . trans('emails.enquiry_confirmation_msg') . '</em></small></p>' . "\n";
                            $message_text .= '<p class="lead strong text-success">' . ucfirst(trans('filters.please')) . ' ' . mb_strtolower(trans('navigation.confirm')) . ' ' . mb_strtolower(trans('emails.your_enquiry')) . ' :</p>' . "\n";
                            //$message_text .= '<div class="spacer-10"></div>' . "\n";
                            //$message_text .= '<h5 class="lead strong accent-color">' . trans('show_ad_detail.looking_for', ['product' => $datasRequest['ad_title']]). '</h5>' . "\n";
                            $message_text .= $form;
                            $message_text .= '<p class="clearfix strong">' . trans('show_ad_detail.transfo_confirm') . '<p>';
                            $message_text .= '<small class="clearfix accent-color"">' . trans('show_ad_detail.transfo_confirmation_registration') . '</small>';

                            $message_text .= '<script>' . "\n";
                            $message_text .= '  ajaxForm($("#form_bod"));' . "\n";
                            $message_text .= '  $("#msgModalAjax_form_enquiry").on("hidden.bs.modal", function (e) {' . "\n";
                            $message_text .= '        $("#form_enquiry .inputs").html(\'<p class="alert alert-success text-success">' . trans('emails.enquiry_confirmation_msg') . '</p>\');' . "\n";
                            $message_text .= '  });' . "\n";
                            $message_text .= '</script>' . "\n";
                            $response = [
                                'success' => true,
                                'message' => $message_text,
                                //'message_title' => '<h4 class="title strong accent-color">' . trans('show_ad_detail.looking_for', ['product' => $product]) . '</h4>',
                                'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                                'success_message' => trans('emails.enquiry_confirmation_msg'),
                                'message_referrer' => 'form_enquiry',
                                'type' => 'transfo'
                            ];
                            $return = response()->json($response);
                            return $return;
                        }
                    }
                }
            } catch(\Exception $e) {
                $error_message = $e->getMessage();
                // prevent GMAIL Timeout
                // Connection to ssl://smtp.gmail.com:465 Timed Out
                if (preg_match('/Connection to ssl/', $error_message) || preg_match('/Timed Out/', $error_message)) {
                    $error_message = trans('errors/503.error_message');
                    $error_message .= '<script>' . "\n";
                    $error_message .= '    $("#msgModalAjax_form_enquiry").delay(5000).queue(function() {' . "\n";
                    $error_message .= '      $("#msgModalAjax_form_enquiry").modal("hide");' . "\n";
                    $error_message .= '      $(this).dequeue();' . "\n";
                    $error_message .= '    });' . "\n";
                    $error_message .= '</script>' . "\n";
                }
                /*$response = [
                    'success' => false,
                    'message' => $error_message
                ];*/
                $response = [
                    'success' => false,
                    'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . '</h4>',
                    'message' => $error_message,
                    'message_referrer' => 'form_enquiry',
                    'errors' => $errors
                ];

                $return = response()->json($response);
                return $return;
            }
        }


        /**
         * Store a newly created bod in storage.
         *
         * @param Request|Request $request
         */
        //public function store(EnquiryFormRequest $request)
        public function bod(Request $request)
        {
            try {
                if($request->ajax()) {
                    $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
                    $_countryCode = (null !== config('youboat.' . $country_code . '.country_code')) ? config('youboat.' . $country_code . '.country_code') : 'GB';
                    $locale = SearchController::getCountryLocaleCode($_countryCode);
                    setlocale(LC_MONETARY, $locale);

                    $datasRequest = $request->all();

                    //==========

                    $datasRequest['ad_id'] =!empty($datasRequest['ad_id']) ? $datasRequest['ad_id'] : '';
                    $datasRequest['ad_url'] = !empty($datasRequest['ad_url']) ? $datasRequest['ad_url'] : '';
                    $datasRequest['ad_title'] = !empty($datasRequest['ad_title']) ? $datasRequest['ad_title'] : '';
                    $datasRequest['ad_type'] = !empty($datasRequest['ad_type']) ? $datasRequest['ad_type'] : '';
                    $datasRequest['ad_category'] = !empty($datasRequest['ad_category']) ? $datasRequest['ad_category'] : '';
                    $datasRequest['ad_subcategory'] = !empty($datasRequest['ad_subcategory']) ? $datasRequest['ad_subcategory'] : '';
                    $datasRequest['ad_manufacturer'] = !empty($datasRequest['ad_manufacturer']) ? $datasRequest['ad_manufacturer'] : '';
                    $datasRequest['ad_model'] = !empty($datasRequest['ad_model']) ? $datasRequest['ad_model'] : '';

                    //==========

                    $datasRequest['adstypes_id'] = !empty($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : '';
                    $datasRequest['categories_ids'] = !empty($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : '';
                    $datasRequest['subcategories_ids'] = !empty($datasRequest['subcategories_ids']) ? $datasRequest['subcategories_ids'] : '';
                    $datasRequest['manufacturers_id'] = !empty($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : '';
                    $datasRequest['models_id'] =!empty($datasRequest['models_id']) ? $datasRequest['models_id'] : '';
                    //----------
                    $datasRequest['ci_firstname'] = !empty($datasRequest['ci_firstname']) ? ucfirst(mb_strtolower($datasRequest['ci_firstname'])) : '';
                    $datasRequest['ci_last_name'] = !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : '';
                    $datasRequest['ci_email'] = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : '';
                    $datasRequest['ci_phone'] = !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : '';
                    //$datasRequest['currency'] = !empty($datasRequest['currency']) ? $datasRequest['currency'] : '';
                    $datasRequest['budget'] = !empty($datasRequest['budget']) ? $datasRequest['budget'] : '';

                    $datasRequest['ci_countries_id'] = !empty($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : '';
                    //$datasRequest['countries_id'] = !empty($datasRequest['countries_id']) ? $datasRequest['countries_id'] : '';
                    $datasRequest['countries_id'] = $datasRequest['ci_countries_id'];
                    $datasRequest['ci_description'] = !empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : '';

                    $product = '';
                    $product .= empty($product) && !empty($datasRequest['ad_manufacturer']) ? $datasRequest['ad_manufacturer'] : '';
                    $product .= !empty($product) && !empty($datasRequest['ad_model']) ? ' ' . $datasRequest['ad_model'] : '';
                    if(empty($product)) {
                        if(!empty($datasRequest['ad_title'])) {
                            $product = $datasRequest['ad_title'];
                        }
                    }
                    if(empty($datasRequest['manufacturers_id']) || $datasRequest['models_id']) {
                        $datasRequest['ci_description'] = trans('show_ad_detail.looking_for', ['product' => $product]) . "\n" . $datasRequest['ci_description'];
                    }

                    //----------
                    $datasRequest['email'] = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : '';
                    //$datasRequest['ci_password'] = str_slug($datasRequest['ci_last_name'], '_') . date('Ymd');
                    $datasRequest['ci_password'] = str_random(8);

                    //var_dump('Auth::check()');
                    //var_dump(Auth::user()->email);
                    //var_dump($datasRequest['ci_email']);

                    $user_check = false;
                    if( Auth::check()
                        && Auth::user()->email == $datasRequest['ci_email']
                    ) {
                        // User already connected
                        // and ci_email equal to user email
                        $user_check = true;
                        $ci_email = Auth::user()->email;

                        $user_id = Auth::user()->id;
                        $datasRequest['user_id'] = $user_id;

                        $ci_username = Auth::user()->username;
                        $datasRequest['username'] = $ci_username;

                        /*$result = CustomersCaracts::where('user_id', $user_id)
                            // @TODO  : check if 'emails' check necessary
                            //->where('emails', $ci_email)
                            ->select('id', 'firstname', 'name')
                            ->get();

                        $result = json_decode(json_encode($result), true);*/
                        ////$customerscaracts = [];
                        ////if(!empty($result)) {
                        ////    $customerscaracts = $result[0];
                        ////}
                        //$customerscaracts = json_decode(json_encode($result), true)[0];
                        ////$customerscaracts['email'] = $ci_email;
                        //var_dump('$customerscaracts');
                        //var_dump($customerscaracts);

                        $result = User::where('id', $user_id)->where('email', $ci_email)->select('password')->get();
                        $result = json_decode(json_encode($result), true);
                        $user_infos = [];
                        if(!empty($result)) {
                            $user_infos = $result[0];
                        }
                        //$user_infos = json_decode(json_encode($result), true)[0];
                        $password                       = 'already_created';
                        $passwordCrypted                = !empty($user_infos['password']) ? $user_infos['password'] : null;
                    } elseif (
                        Auth::check()
                        //&& Auth::user()->email != $datasRequest['ci_email']
                        && !empty(json_decode(json_encode($result = User::select('id', 'username', 'password')->where('email', '=', $datasRequest['ci_email'])->get()), true))
                    ) {
                        // User already loggued
                        // and ci_email already exists into user table
                        // so User loggued email != ci_email
                        if(Auth::check()) {
                            Auth::logout();
                            Session::flush();
                        }
                        $user_check = true;
                        $ci_email = $datasRequest['ci_email'];

                        $result = json_decode(json_encode($result), true);
                        $user_infos = [];
                        if(!empty($result)) {
                            $user_infos = $result[0];
                        }
                        //$user_infos = json_decode(json_encode($result), true)[0];

                        $user_id = !empty($user_infos['id']) ? $user_infos['id'] : null;
                        $datasRequest['user_id'] = $user_id;
                        $datasRequest['username'] = !empty($user_infos['username']) ? $user_infos['username'] : null;

                        $password                       = 'already_created';
                        $passwordCrypted                = !empty($user_infos['password']) ? $user_infos['password'] : null;

                        /*$result = CustomersCaracts::where('user_id', $user_id)
                            // @TODO  : check if 'emails' check necessary
                            //->where('emails', $ci_email)
                            ->select('id', 'firstname', 'name')
                            ->get();
                        $result = json_decode(json_encode($result), true);*/
                        ////$customerscaracts = [];
                        ////if(!empty($result)) {
                        ////    $customerscaracts = $result[0];
                        ////}
                        //$customerscaracts = json_decode(json_encode($result), true)[0];
                        ////$customerscaracts['email'] = $ci_email;
                    } elseif (
                        !Auth::check()
                        && !empty(json_decode(json_encode($result = User::select('id', 'username', 'password')->where('email', '=', $datasRequest['ci_email'])->get()), true))
                    ) {
                        // User not loggued
                        // and ci_email already exists into user table
                        $user_check = true;
                        $ci_email = $datasRequest['ci_email'];

                        $result = json_decode(json_encode($result), true);
                        $user_infos = [];
                        if(!empty($result)) {
                            $user_infos = $result[0];
                        }
                        //$user_infos = json_decode(json_encode($result), true)[0];

                        $user_id = !empty($user_infos['id']) ? $user_infos['id'] : null;
                        $datasRequest['user_id'] = $user_id;
                        $datasRequest['username'] = !empty($user_infos['username']) ? $user_infos['username'] : null;

                        $password                       = 'already_created';
                        $passwordCrypted                = !empty($user_infos['password']) ? $user_infos['password'] : null;

                        /*$result = CustomersCaracts::where('user_id', $user_id)
                            // @TODO  : check if 'emails' check necessary
                            //->where('emails', $ci_email)
                            ->select('id', 'firstname', 'name')
                            ->get();
                        $result = json_decode(json_encode($result), true);*/
                        ////$customerscaracts = [];
                        ////if(!empty($result)) {
                        ////    $customerscaracts = $result[0];
                        ////}
                        //$customerscaracts = json_decode(json_encode($result), true)[0];
                        ////$customerscaracts['email'] = $ci_email;
                    } else {
                        // User not loggued
                        // and ci_email does not exist into user table
                        /*if(Auth::check()) {
                            Auth::logout();
                            Session::flush();
                        }*/
                        $user_check = false;
                        $password                       = !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null;
                        $passwordCrypted                = !empty($datasRequest['ci_password']) ? bcrypt($datasRequest['ci_password']) : null;
                        //$passwordCrypted                = !empty($datasRequest['ci_password']) ? Hash::make($datasRequest['ci_password']) : null;

                        // username base on firstname and name
                        //$datasRequest['username']  = !empty($datasRequest['username']) ? $datasRequest['username'] : (!empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? str_slug(mb_strtolower($datasRequest['ci_firstname'])[0] . mb_strtolower($datasRequest['ci_last_name']), '_') : str_slug(mb_strtolower($datasRequest['ci_last_name']), '_') : '');

                        // username base on email
                        list($username, $mailer) = explode('@', $datasRequest['ci_email']);
                        $username = preg_replace('/\./', '_', $username);
                        $username = str_replace('+', '_', $username);
                        //$username = snake_case($username);
                        $username = str_slug($username, '_');

                        // if username exist create it with incremental number
                        $z = 1;
                        while(!empty(json_decode(json_encode($result = User::select('id')->where('username', '=', $username)->get()), true))) {
                            $username = $username . $z;
                            $z++;
                        }

                        $datasRequest['username'] = $username;
                        //$datasRequest['user_id'] = !empty($datasRequest['user_id']) ? $datasRequest['user_id'] : '';
                    }

                    $datasRequest['ci_password']    = $password;
                    $datasRequest['password']       = $password;
                    $datasRequest['role_id']        = 6; //default 6 as 'customer account role',
                    $datasRequest['type']           = 'customer';
                    $datasRequest['status']         = 'active';
                    //----------
                    //$datasRequest['reference'] = 'bod_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'];
                    //$datasRequest['reference'] = 'bod_enquiry_' . $country_code . '_' . $datasRequest['ad_id'] . '_' . date('Ymd') . '_' . $datasRequest['username'];
                    //$datasRequest['reference'] = 'bod_enquiry_' . $country_code . '_' . $datasRequest['ad_id'] . '_' . $datasRequest['username'];
                    $datasRequest['reference'] = 'bod_enquiry_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'] . '_' . $datasRequest['ad_id'];

                    $datasRequest['agree_similar'] = 1;
                    $datasRequest['agree_emails'] = 1;
                    $datasRequest['agree_cgv'] = 1;

                    //==========

                    //if(Auth::check()) {
                    if($user_check) {
                        $rulesBoatOnDemand = BodFormRequest::rulesUpdate();
                    } else {
                        $rulesBoatOnDemand = BodFormRequest::rules();
                    }
                    $validator = Validator::make($datasRequest, $rulesBoatOnDemand);
                    if($validator->fails()) {
                        $errors = $validator->errors();
                        /*$response = [
                            'success' => false,
                            'message' => $errors
                        ];*/
                        $message_modal = '<ul>';
                        $message_modal .= implode('', $errors->all('<li>:message</li>'));
                        $message_modal .= '</ul>';
                        $response = [
                            'success' => false,
                            'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                            'message' => $message_modal,
                            'message_referrer' => 'form_bod',
                            'errors' => $errors
                        ];
                        $return = response()->json($response);
                        return $return;
                    } else {
                        $datasRequest['ci_description'] = ''; // remove comments from enquiry form not needed for BOD creation
                        $BoatOnDemand = BodCaracts::firstOrNew(array('reference' => $datasRequest['reference']));
                        $BoatOnDemand->fill($datasRequest)->save();

                        $BoatOnDemandId = $BoatOnDemand['id'];

                        $updateBodCustomerId = BodCaracts::find($BoatOnDemandId);

                        //if(Auth::check()) {
                        if($user_check) {
                            //$updateBodCustomerId = BodCaracts::find($BoatOnDemandId);

                            ////$updateBodCustomerId->customer_id = $customerscaracts['id'];
                            $updateBodCustomerId->user_id = $user_id;
                            //$updateBodCustomerId->save();
                        } else {
                            $datasRequest['ci_password'] = $datasRequest['password'] = $password;

                            $rulesUser = [
                                'username' => 'required|max:255',
                                'email' => 'required|email|max:255|unique:users',
                                'password' => 'required|min:6',
                                //'g-recaptcha-response' => 'required|recaptcha',
                            ];
                            $validatorUser = Validator::make($datasRequest, $rulesUser);
                            if ($validatorUser->fails()) {
                                $errors = $validatorUser->errors();
                                /*$response = [
                                    'success' => false,
                                    'message' => $errors
                                ];*/
                                $message_modal = '<ul>';
                                $message_modal .= implode('', $errors->all('<li>:message</li>'));
                                $message_modal .= '</ul>';
                                $response = [
                                    'success' => false,
                                    'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                                    'message' => $message_modal,
                                    'message_referrer' => 'form_bod',
                                    'errors' => $errors
                                ];
                                $return = response()->json($response);
                                return $return;
                            } else {
                                $datasRequest['password'] = $passwordCrypted;
                                $User = User::create([
                                    'username' => $datasRequest['username'],
                                    'email' => $datasRequest['email'],
                                    'password' => $datasRequest['password'],
                                    'role_id' => $datasRequest['role_id'],
                                    'type' => $datasRequest['type'],
                                    'status' => $datasRequest['status'],
                                ]);

                                if ($User->save()) {
                                    $datasRequest['user_id'] = $User["id"];
                                    $rulesCustomer = [
                                        'user_id' => 'required'
                                    ];
                                    $validatorCustomer = Validator::make($datasRequest, $rulesCustomer);

                                    if ($validatorCustomer->fails()) {
                                        $errors = $validatorCustomer->errors();
                                        /*
                                        $response = [
                                            'success' => false,
                                            'message' => $errors
                                        ];
                                        */
                                        $message_modal = '<ul>';
                                        $message_modal .= implode('', $errors->all('<li>:message</li>'));
                                        $message_modal .= '</ul>';
                                        $response = [
                                            'success' => false,
                                            'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                                            'message' => $message_modal,
                                            'message_referrer' => 'form_bod',
                                            'errors' => $errors
                                        ];
                                        $return = response()->json($response);
                                        return $return;
                                    } else {
                                        $datasRequest['reference'] = 'bod_enquiry_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . $datasRequest['username'] . '_' . $datasRequest['ad_id'];
                                        $Customer = CustomersCaracts::create([
                                            'user_id' => $User["id"],
                                            'firstname' => !empty($datasRequest['ci_firstname']) ? ucfirst(mb_strtolower($datasRequest['ci_firstname'])) : null,
                                            'name' => !empty($datasRequest['ci_last_name']) ? mb_strtoupper($datasRequest['ci_last_name']) : null,
                                            'address' => '',
                                            'address_more' => '',
                                            'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                            'city' => !empty($datasRequest['ci_city']) ? mb_strtoupper($datasRequest['ci_city']) : null,
                                            'province' => '',
                                            'region' => '',
                                            'subregion' => '',
                                            'country_id' => !empty($datasRequest['ci_countries_id']) ? $datasRequest['ci_countries_id'] : null,
                                            'phone_1' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                            'phone_mobile' => '',
                                            'fax' => '',
                                            'emails' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                            'twitter' => '',
                                            'facebook' => '',
                                            'origin' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                                        ]);

                                        if ($Customer->save()) {
                                            //$updateBodCustomerId = BodCaracts::find($BoatOnDemandId);
                                            //$updateBodCustomerId->customer_id = $Customer['id'];
                                            $updateBodCustomerId->user_id = $User["id"];
                                            //$updateBodCustomerId->save();
                                        }
                                    }
                                }
                            }
                            if ($User->save() && $Customer->save()) {
                                $user_check = true;
                            }
                        }

                        //if ($BoatOnDemand->save()) {
                        if ($updateBodCustomerId->save()) {
                            $bod_check = true;
                        }

                        if ($user_check && $bod_check) {
                            // Don(t nedd to save in ProspectiveCustomers becasu already done when creating enquiry request
                            $inputProspectiveCustomers = array(
                                'ci_firstname' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                'ci_last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                'ci_email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                'ci_phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                'country_code' => $country_code,
                                'referrer' => 'bod_enquiry',
                                'ci_description' => '',
                                'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null
                            );

                            //$ProspectiveCustomers = ProspectiveCustomers::firstOrNew(array('ci_email' => $datasRequest['ci_email']));
                            //if ($ProspectiveCustomers->fill($inputProspectiveCustomers)->save()) {
                            $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                            if ($ProspectiveCustomers->save()) {
                                $updateBodProspectiveCustomerId = BodCaracts::find($BoatOnDemandId);
                                $updateBodProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                                $updateBodProspectiveCustomerId->save();
                            }

                            $details = [];
                            if(!empty($datasRequest)) {
                                //$boat_locations = config('youboat.'. $country_code .'.locations');
                                //$boat_locations_regions = $boat_locations['regions'];
                                //$boat_locations_counties = $boat_locations['counties'];

                                $details = array(
                                    /****
                                    'adstype' => !empty($datasRequest['ad_type']) ? $datasRequest['ad_type'] : null,
                                    'category' => !empty($datasRequest['ad_category']) ? $datasRequest['ad_category'] : null,
                                    'subcategory' => !empty($datasRequest['ad_subcategory']) ? $datasRequest['ad_subcategory'] : null,
                                    'manufacturer' => !empty($datasRequest['ad_manufacturer']) ? $datasRequest['ad_manufacturer'] : null,
                                    'model' => !empty($datasRequest['ad_model']) ? $datasRequest['ad_model'] : null,
                                    ****/

                                    'adstype' => !empty($datasRequest['adstypes_id']) ? $this->getAdsTypeById($datasRequest['adstypes_id'])['name'] : null,
                                    'category' => !empty($datasRequest['categories_ids']) ? $this->getCategoryById($datasRequest['categories_ids'])['name'] : null,
                                    'subcategory' => !empty($datasRequest['subcategories_ids']) ? $this->getSubcategoryById($datasRequest['subcategories_ids'])['name'] : null,

                                    'looking_for' => !empty($product) ? $product : null,

                                    'manufacturer' => empty($product) && !empty($datasRequest['manufacturers_id']) ? $this->getManufacturerById($datasRequest['manufacturers_id'])['name'] : null,
                                    'model' => empty($product) && !empty($datasRequest['models_id']) ? $this->getModelById($datasRequest['models_id'])['name'] : null,

                                    /*
                                    'manufacturer_engines' => !empty($datasRequest['manufacturersengines_id']) ? $this->getManufacturerEngineById($datasRequest['manufacturersengines_id'])['name'] : null,
                                    'model_engine' => !empty($datasRequest['modelsengines_id']) ? $this->getModelEngineById($datasRequest['modelsengines_id'])['name'] : null,

                                    'min_year_built' => !empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null,
                                    'max_year_built' => !empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null,

                                    'min_length' => !empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null,
                                    'max_length' => !empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null,

                                    'min_width' => !empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null,
                                    'max_width' => !empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null,
                                    'country' => !empty($datasRequest['countries_id']) ? $this->getCountryById($datasRequest['countries_id'])['name'] : null,
                                    'region' => !empty($datasRequest['regions_id']) ? $boat_locations_regions[$datasRequest['regions_id']]['name'] : null,
                                    'county' => !empty($datasRequest['counties_id']) ? $boat_locations_counties[$datasRequest['counties_id']] : null,
                                    */
//                                    'budget' => !empty($datasRequest['budget']) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $datasRequest['budget']))) : null,
                                    //'budget' => !empty($datasRequest['budget']) ? formatPrice($datasRequest['budget'], $datasRequest['countries_id']) : null,
                                    'budget' => !empty($datasRequest['budget']) ? $datasRequest['budget'] : null,
                                    'sell_type' => !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null,
                                    'description' => !empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null,
                                    /*
                                    'with_marina_berth' => (!empty($datasRequest['with_marina_berth']) && $datasRequest['with_marina_berth'] == 1) ? ucfirst(trans('boat_on_demand.with_marina_berth')) : null,
                                    'agree_similar' => (!empty($datasRequest['agree_similar']) && $datasRequest['agree_similar'] == 1) ? ucfirst(trans('boat_on_demand.agree_similar')) : null,
                                    */

                                    /*
                                    'recovery_adstype' => !empty($datasRequest['recovery_adstypes_id']) ? $this->getAdsTypeById($datasRequest['recovery_adstypes_id'])['name'] : null,
                                    'recovery_category' => !empty($datasRequest['recovery_categories_ids']) ? $this->getCategoryById($datasRequest['recovery_categories_ids'])['name'] : null,
                                    'recovery_subcategory' => !empty($datasRequest['recovery_subcategories_ids']) ? $this->getSubcategoryById($datasRequest['recovery_subcategories_ids'])['name'] : null,

                                    'recovery_manufacturer' => !empty($datasRequest['recovery_manufacturers_id']) ? $this->getManufacturerById($datasRequest['recovery_manufacturers_id'])['name'] : null,
                                    'recovery_model' => !empty($datasRequest['recovery_models_id']) ? $this->getModelById($datasRequest['recovery_models_id'])['name'] : null,
                                    'recovery_year_built' => !empty($datasRequest['recovery_year_built']) ? $datasRequest['recovery_year_built'] : null,
                                    'recovery_manufacturer_engine' => !empty($datasRequest['recovery_manufacturersengines_id']) ? $this->getManufacturerEngineById($datasRequest['recovery_manufacturersengines_id'])['name'] : null,
                                    'recovery_model_engine' => !empty($datasRequest['recovery_modelsengines_id']) ? $this->getModelEngineById($datasRequest['recovery_modelsengines_id'])['name'] : null,

                                    'recovery_description' => !empty($datasRequest['recovery_description']) ? $datasRequest['recovery_description'] : null,

                                    'recovery_budget' => !empty($datasRequest['recovery_budget']) ? $datasRequest['recovery_budget'] . $datasRequest['currency'] : null,
                                    */
                                    'first_name' => !empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null,
                                    'last_name' => !empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null,
                                    'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                    'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                                    'phone' => !empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null,
                                    /*
                                    'zip' => !empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null,
                                    'city' => !empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null,
                                    */
                                    'country' => !empty($datasRequest['ci_countries_id']) ? $this->getCountryById($datasRequest['ci_countries_id'])['name'] : null,
                                    /*
                                    'region' => !empty($datasRequest['ci_regions_id']) ? $boat_locations_regions[$datasRequest['ci_regions_id']]['name'] : null,
                                    'county' => !empty($datasRequest['ci_counties_id']) ? $boat_locations_counties[$datasRequest['ci_counties_id']] : null,
                                    'agree_emails' => (!empty($datasRequest['agree_emails']) && $datasRequest['agree_emails'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_emails')) : null
                                    */
                                );
                                /*
                                $title  = htmlspecialchars_decode(title_case(trans('navigation.cgv')));
                                $url    = url(trans_route($currentLocale, 'routes.cgv'));
                                $terms_link = '<a href="' . $url . '" title="' . title_case($title) . '" target="_blank">' . $title .'</a>';
                                $details['agree_cgv'] = (!empty($datasRequest['agree_cgv']) && $datasRequest['agree_cgv'] == 1) ? ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms'=>$terms_link,'website_name'=>$country_code . '.' . env('APP_NAME')])) : null;
                                */
                            }

                            $datasEmail = array(
                                //'reference' => !empty($datasRequest['reference']) ? $datasRequest['reference'] : null,
                                'details' => $details,
                                'website_name' => config('youboat.' . $country_code . '.website_name'),
                                'type_request' => 'a Boat On Demand request',
                                'name' => !empty($datasRequest['ci_last_name']) ? !empty($datasRequest['ci_firstname']) ? ucfirst(mb_strtolower($datasRequest['ci_firstname'])) . ' ' . mb_strtoupper($datasRequest['ci_last_name']) : mb_strtoupper($datasRequest['ci_last_name']) : null,
                                'email' => !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null,
                                'password' => !empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null,
                                'country_code' => $country_code,
                                //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                                'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                                'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                                'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                            );

                            Mail::send('emails.bod', $datasEmail, function ($message) use ($datasEmail) {
                                $message->subject('Boat On Demand' . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.boat_on_demand') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                $message->to($datasEmail['email'], $datasEmail['name']);
                            });

                            $datasEmail['reference'] = !empty($datasRequest['reference']) ? $datasRequest['reference'] : null;
                            Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                                $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                                $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.boat_on_demand') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                                $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                                //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                                $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                            });

                            $message_text = '';
                            /*$customer_denomination  = '';
                            if(array_key_exists('ci_last_name', $datasRequest) && !empty($datasRequest["ci_last_name"])) {
                                $customer_denomination .= $datasRequest['ci_last_name'];
                            }
                            if(array_key_exists('ci_firstname', $datasRequest) && !empty($datasRequest["ci_firstname"])) {
                                $customer_denomination .= ' ' . $datasRequest['ci_firstname'];
                            }
                            $message_text .= !empty($customer_denomination) ? '<strong>' . $customer_denomination . '</strong>, <br>' : '';
                            */
                            $message_text .= '<p class="text-success">' . trans('emails.enquiry_confirmation_msg') . '</p>';
                            $message_text .= '<script>' . "\n";
                            //$message_text .= '  $("#msgModal").on("shown.bs.modal", function (e) {' . "\n";
                            $message_text .= '    $("#msgModalAjax_form_enquiry").delay(250).queue(function() {' . "\n";
                            $message_text .= '      $("#msgModalAjax_form_enquiry").modal("hide");' . "\n";
                            $message_text .= '      $(this).dequeue();' . "\n";
                            $message_text .= '    });' . "\n";
                            //$message_text .= '    window.setTimeout(function(){' . "\n";
                            //$message_text .= '        $("#msgModalAjax_form_enquiry").modal("hide");' . "\n";
                            //$message_text .= '    }, 2000);' . "\n";
                            //$message_text .= '  });' . "\n";
                            $message_text .= '  $("#msgModalAjax_form_enquiry").on("hidden.bs.modal", function (e) {' . "\n";
                            $message_text .= '    gotoElementTop($(\'#form_enquiry\'));' . "\n";
                            $message_text .= '  });' . "\n";
                            $message_text .= '  $("#form_enquiry .inputs").html(\'<p class="alert alert-success text-success">' . trans('emails.enquiry_confirmation_msg') . '</p>\');' . "\n";
                            $message_text .= '</script>' . "\n";

                            if(!Auth::check() && !empty($User)) {
                                Auth::login($User);
                            }

                            $response = [
                                'success' => true,
                                'message' => $message_text,
                                //'message_title' => '<h4 class="title">' . trans('navigation.boat_on_demand') . '</h4>',
                                //'message_title' => '<h4 class="title">' . trans('navigation.send_enquiry') . '</h4>',
                                'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . ' ' . trans('navigation.for') . ' &laquo; ' . $product . ' &raquo;</h4>',
                                //'success_message' => trans('emails.thanks_boat_on_demand'),
                                'message_referrer' => 'form_bod',
                                'success_message' => trans('emails.enquiry_confirmation_msg')
                            ];

                            $return = response()->json($response);
                            return $return;
                        }
                    }
                }
            } catch(\Exception $e) {
                $error_message = $e->getMessage();
                // prevent GMAIL Timeout
                // Connection to ssl://smtp.gmail.com:465 Timed Out
                if (preg_match('/Connection to ssl/', $error_message) || preg_match('/Timed Out/', $error_message)) {
                    $error_message = trans('errors/503.error_message');
                    $error_message .= '<script>' . "\n";
                    $error_message .= '    $("#msgModalAjax_form_bod").delay(5000).queue(function() {' . "\n";
                    $error_message .= '      $("#msgModalAjax_form_bod").modal("hide");' . "\n";
                    $error_message .= '      $(this).dequeue();' . "\n";
                    $error_message .= '    });' . "\n";
                    $error_message .= '</script>' . "\n";
                }
                /*$response = [
                    'success' => false,
                    'message' => $error_message
                ];*/
                $response = [
                    'success' => false,
                    'message_title' => '<h4 class="title strong accent-color">' . trans('navigation.contact_the_seller') . '</h4>',
                    'message' => $error_message,
                    'message_referrer' => 'form_bod',
                    'errors' => $errors
                ];

                $return = response()->json($response);
                return $return;
            }
        }
    }
