<?php namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;

    use App\News;

    use DB;

    class HomeController extends ForsaleController
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
        public function index (Request $request)
        {
            $viewName       = app('request')->route()->getName();
            if ($request) {
                $currentCountryCode = mb_strtolower(!empty(config('app.country_code')) ? config('app.country_code') : 'uk');
                $currentCountryCode = preg_replace('/gb/i', 'uk', $currentCountryCode);
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
                    ->where('status', '=', 'valid')
                    ->where('news_country_code', '=', $currentCountryCode)
                    //->whereDate('start_date', '<=', $now)
                    ->whereDate('end_date', '>=', $now)
                    ->orderBy('start_date', 'asc')
                    ->get();
                $result = json_decode(json_encode($result), true);

                $return = [];
                if(!empty($result)) {
                    $return['latest_news'] = $result;
                }

                $return += $this->showAds($request);
                return view($viewName, $return);
            } else {
                return view($viewName);
            }
        }
    }
