<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Http\Requests\NewsRequest as NewsRequest;

    use App\Http\Requests;
    //use App\Http\Controllers\Controller;
    use App\Http\Controllers\Traits\FileUploadTrait;

    use App\News;
    use App\CustomersCaracts;
    use App\CommercialsCaracts;
    use App\DealersCaracts;
    use App\PrivatesCaracts;
    use App\ProspectiveCustomers;

    use Mail;
    use Auth;
    use Cache;

    use Illuminate\Support\Facades\Validator;

    class NewsController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware(['clearcache']);
        }

        /**
         * Show the application news form.
         *
         * @return \Illuminate\Http\Response
         */
        public function create ()
        {
            //$viewName       = 'news_create';
            $viewName = app('request')->route()->getName();
            $author_name = $author_email = $author_phone = '';

            $start_date = '2017-05-03';

            if(Auth::check()) {
                $author_email = Auth::user()->email;
                $user_id = Auth::user()->id;
                $user_type = Auth::user()->type;

                $usercaracts = '';
                switch($user_type) {
                    case 'admin':
                        $usercaracts = [];
                        break;
                    case 'private':
                        $usercaracts = PrivatesCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'phone_1' )->get();
                        break;
                    case 'dealer':
                        $usercaracts = DealersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'phone_1' )->get();
                        break;
                    case 'customer':
                        $usercaracts = CustomersCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'phone_1' )->get();
                        break;
                    case 'commercial':
                        $usercaracts = CommercialsCaracts::where('user_id', $user_id)->select('id', 'firstname', 'name', 'phone_1' )->get();
                        break;
                }
                if(isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $user_caracts = $array[0];
                        $user_caracts = $array[0];
                        $author_name = !empty($user_caracts['firstname']) ? ucwords(mb_strtolower($user_caracts['firstname'])) : '';
                        $author_name .= !empty($author_name) ? ' ' : '';
                        $author_name .= !empty($user_caracts['name']) ? mb_strtoupper($user_caracts['name']) : '';
                        $author_phone = !empty($user_caracts['phone_1']) ? $user_caracts['phone_1'] : '';
                    }
                }
                /*
                $result = CustomersCaracts::where('user_id', $user_id)
                    ->select('id', 'firstname', 'name', 'phone_1' )
                    ->get();
                $result = json_decode(json_encode($result), true);
                //$customerscaracts = [];
                if(!empty($result)) {
                    $customerscaracts = $result[0];
                    $author_name = !empty($customerscaracts['firstname']) ? ucwords(mb_strtolower($customerscaracts['firstname'])) : '';
                    $author_name .= !empty($author_name) ? ' ' : '';
                    $author_name .= !empty($customerscaracts['name']) ? mb_strtoupper($customerscaracts['name']) : '';
                    $author_phone = !empty($customerscaracts['phone_1']) ? $customerscaracts['phone_1'] : '';
                }
                */
            }
            return view($viewName, compact('author_name', 'author_email', 'author_phone'));
        }

        /**
         * Store a newly created news in storage.
         *m
         * @param Request|Request $request
         */
        //public function store(NewsRequest $request)
        public function store(Request $request)
        {
            $currentLocale = config('app.locale');
            $locale = SearchController::getCountryLocaleCode(mb_strtoupper(str_replace('en', 'gb', $currentLocale)));
            setlocale(LC_MONETARY, $locale);

            $viewName       = 'news_create';

            try {
                //$request = $this->saveFiles($request);
                $datasRequest = $request->all();

                if(!empty($datasRequest['start_date'])) {
                    $datasRequest['start_date'] = str_replace('/', '-', $datasRequest['start_date']);
                    $datasRequest['start_date'] = date('Y-m-d', strtotime($datasRequest['start_date']));
                }
                if(!empty($datasRequest['end_date'])) {
                    $datasRequest['end_date'] = str_replace('/', '-', $datasRequest['end_date']);
                    $datasRequest['end_date'] = date('Y-m-d', strtotime($datasRequest['end_date']));
                }

                $start_date = $datasRequest['start_date'] = !empty($datasRequest['start_date']) ? $datasRequest['start_date'] : date("Y-m-d");
                $country_code     = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
                //var_dump($datasRequest);
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    if($file->isValid()) {
                        $title = !empty($datasRequest['title']) ? $datasRequest['title'] : trans('news.post');
                        $title = str_slug(mb_strtolower($title), '-');

                        $rewrite_url = !empty($datasRequest['title']) ? '/news/' . $country_code . '/' . $start_date . '/' . $title : '';

                        $z = 1;
                        while(!empty(json_decode(json_encode($result = News::select('id')->where('rewrite_url', '=', $rewrite_url)->get()), true))) {
                            $rewrite_url = '/news/' . $country_code . '/' . $start_date . '/' . $title . '-' . $z;
                            $z++;
                        }
                        $datasRequest['rewrite_url'] = $rewrite_url;

                        $destinationPath = '/assets';
                        $destinationPath .= $datasRequest['rewrite_url'] . '/';
                        //$destinationPath .= $start_date . '/' . $title . '/';
                        //$imageTempName = $file->getPathname();
                        //$imageName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension(); // getting file extension
                        $fileName = $title;
                        $fileName .= '_' . $start_date;
                        $fileName .= '.' . $extension;

                        $pathinfo = pathinfo($fileName);

                        $z = 1;
                        while(file_exists(public_path() . $destinationPath . $fileName)) {
                            $fileName = $pathinfo['filename'] . '_' . $z . '.' . $extension;
                            $z++;
                        }

                        // move file photo to news directory localized and rename it
                        $file->move(public_path() . $destinationPath, $fileName);

                        $datasRequest['photo'] = $destinationPath . $fileName;
                    }
                }
                $datasRequest['news_country_code'] = $country_code;
                $datasRequest['author_name']    = !empty($datasRequest['author_name']) ? ucwords(mb_strtolower($datasRequest['author_name'])) : null;
                $datasRequest['author_email']   = !empty($datasRequest['author_email']) ? mb_strtolower($datasRequest['author_email']) : null;
                $datasRequest['author_phone']   = !empty($datasRequest['author_phone']) ? $datasRequest['author_phone'] : null;
                $datasRequest['author_url']     = !empty($datasRequest['author_url']) ? $datasRequest['author_url'] : null;

                $datasRequest['title']          = !empty($datasRequest['title']) ? ucwords(mb_strtolower($datasRequest['title'])) : null;
                $datasRequest['date']           = !empty($datasRequest['date']) ? $datasRequest['date'] : null;
                $datasRequest['intro']          = !empty($datasRequest['intro']) ? $datasRequest['intro'] : null;
                $datasRequest['description']    = !empty($datasRequest['description']) ? $datasRequest['description'] : null;
                $datasRequest['url']            = !empty($datasRequest['url']) ? $datasRequest['url'] : null;
                //$datasRequest['photo']          = !empty($datasRequest['photo']) ? $datasRequest['photo'] : null;
                $datasRequest['category']       = !empty($datasRequest['category']) ? $datasRequest['category'] : null;
                //$datasRequest['rewrite_url']    = !empty($datasRequest['start_date']) && !empty($datasRequest['title']) ? $start_date . '/' . $title . '/' : '';
                $datasRequest['end_date']       = !empty($datasRequest['end_date']) ?  $datasRequest['end_date'] : date('Y-m-d', strtotime('+1 year', strtotime($datasRequest['start_date'])));
                $datasRequest['status']         = 'in_moderation';

                $rulesNews = NewsRequest::rules();
                $validator = Validator::make($datasRequest, $rulesNews);
                if($validator->fails()) {
                    @unlink(public_path() . $destinationPath . $fileName);
                    $errors = $validator->errors();
                    $array = $datasRequest;
                    $array['errors'] = $errors;

                    $return = $array;
                    return view($viewName, $return)->withInput($request->input())->withErrors($errors, $this->errorBag());
                } else {
                    $News = News::Create($datasRequest);
                    //$News = News::firstOrNew($datasRequest);
                    if ($News->save()) {

                        $datasRequest['reference']      = 'news_' . $country_code . '_' . $_SERVER['REQUEST_TIME'] . '_' . str_slug(mb_strtolower($datasRequest['author_name']) , '_');
                        $inputProspectiveCustomers = array(
                            'ci_firstname' => null,
                            'ci_last_name' => $datasRequest['author_name'],
                            'ci_email' => $datasRequest['author_email'],
                            'ci_phone' => $datasRequest['author_phone'],
                            'country_code' => $country_code,
                            'referrer' => 'bod',
                            'ci_description' => '',
                            'reference' => $datasRequest['reference']
                        );

                        $ProspectiveCustomers = ProspectiveCustomers::Create($inputProspectiveCustomers);
                        if ($ProspectiveCustomers->save()) {
                            //$updateNewsProspectiveCustomerId = News::find($News['id']);
                            //$updateNewsProspectiveCustomerId->prospective_customer_id = $ProspectiveCustomers['id'];
                            //$updateNewsProspectiveCustomerId->save();
                        }

                        $details = [];
                        if(!empty($datasRequest)) {
                            $details = array(
                                //'author_name' => $datasRequest['author_name'],
                                //'author_email' => $datasRequest['author_email'],
                                //'author_phone' => $datasRequest['author_phone'],
                                //'author_url' => $datasRequest['author_url'],
                                'title' => $datasRequest['title'],
                                'date' => $datasRequest['date'],
                                'url' => $datasRequest['url'],
                                'intro' => $datasRequest['intro'],
                                'description' => $datasRequest['description'],
                                //'photo' => $datasRequest['photo'],
                                'category' => $datasRequest['category'],
                                'start_date' => $datasRequest['start_date'],
                                'end_date' => $datasRequest['end_date']
                            );
                        }
                        $datasEmail = array(
                            'details' => $details,
                            'website_name' => config('youboat.' . $country_code . '.website_name'),
                            'type_request' => 'a News submission',
                            'name' => $datasRequest['author_name'],
                            'email' => $datasRequest['author_email'],
                            //'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc') . ',' . config('youboat.' . $country_code . '.country_manager_email'),
                            'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                            'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                            'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME'),
                        );
                        Mail::send('emails.news', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject('News' . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.news_create') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            $message->to($datasEmail['email'], $datasEmail['name']);
                        });

                        $datasEmail['photo'] = $datasRequest['photo'];
                        $datasEmail['reference'] = $datasRequest['reference'];
                        Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                            $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                            $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.news_create') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                            $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                            //$message->bcc(explode(',', $datasEmail['bcc_mails']));
                            $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                        });

                        $message_referrer = 'news_create';
                        $message_title = trans('navigation.news_create');
                        $message_text = trans('emails.thanks_news_create');
                        $message_type = 'success';
                        $request->session()->put('message.referrer', $message_referrer);
                        $request->session()->put('message.title', $message_title);
                        $request->session()->put('message.text', $message_text);
                        $request->session()->put('message.type', $message_type);

                        $message = session()->get('message');

                        return redirect(trans_route($currentLocale, '/'))->withMessage($message);
                    }
                }
            } catch(\Exception $e) {
                return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
            }
        }

        /**
         * showAds
         *
         * @return \Illuminate\Http\Response
         */
        public function showNewsDetail(Request $request)
        {
            $currentLocale = config('app.locale');

            if ($request) {
                $viewName       = app('request')->route()->getName();
                $routeParameters = $request->route()->parameters();

                $rewrite_url = '';
                if(!empty($routeParameters)) {
                    $rewrite_url =  '/' . trans('routes.news_detail') . '/' . $routeParameters["country_code"] . '/' . $routeParameters["date"] . '/' . $routeParameters["title_slug"];
                }
                $now = date("Y-m-d");
                $result = News::select(
                    'author_name',
                    'author_email',
                    'author_phone',
                    'author_url',
                    'url',
                    'title',
                    'intro',
                    'description',
                    'photo',
                    'date',
                    'category',
                    'rewrite_url',
                    'start_date',
                    'end_date',
                    'status'
                )
                    ->where('rewrite_url', $rewrite_url)
                    ->where('status', '=', 'valid')
                    //->whereDate('start_date', '<=', $now)
                    ->whereDate('end_date', '>=', $now)
                    ->get();
                $result = json_decode(json_encode($result), true);

                if(!empty($result)) {
                    $news_detail = $result[0];

                    $now = date("Y-m-d");
                    $result = News::select(
                        'author_name',
                        'author_email',
                        'author_phone',
                        'author_url',
                        'url',
                        'title',
                        'intro',
                        'description',
                        'photo',
                        'date',
                        'category',
                        'rewrite_url',
                        'start_date',
                        'end_date',
                        'status'
                    )
                        ->where('rewrite_url', '!=', $rewrite_url)
                        ->where('status', '=', 'valid')
                        //->whereDate('start_date', '<=', $now)
                        ->whereDate('end_date', '>=', $now)
                        ->orderBy('start_date', 'asc')
                        ->get();
                    $result = json_decode(json_encode($result), true);

                    $return = [];
                    if(!empty($result)) {
                        $return['latest_news'] = $result;
                    }

                    return view($viewName, compact('routeParameters') + $news_detail + $return)->with(['controller'=>$this]);
                } else {
                    $message_referrer = 'news';
                    $message_title = trans('navigation.news_detail') . ' ' . trans('errors/404.error_title');
                    $message_text = trans('news.url') . ' ' . trans('news.not_valid');
                    $message_type = 'error';
                    $request->session()->put('message.referrer', $message_referrer);
                    $request->session()->put('message.title', $message_title);
                    $request->session()->put('message.text', $message_text);
                    $request->session()->put('message.type', $message_type);

                    $message = session()->get('message');

                    return redirect('404')->withMessage($message);
                }

            } else {
                return redirect(trans_route($currentLocale, '/'), 301);
            }
        }
    }
