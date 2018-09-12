<?php namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;

    class PagesController extends Controller
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
         * Change locaze
         *
         * @return \Illuminate\Http\Response
         */
        public function changeLocale(Request $request) {
            if(empty($request->locale)) {
                redirect()->back()->withMessage('Unknown locale, please, if problem persists, contact admin.');
            }
            session()->put('locale', $request->locale);
            return redirect()->back()->withMessage('Your locale has now been changed to <b>'.$request->locale.'</b>')->withMessageType('success');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //$action = app('request')->route()->getAction();
            //$viewName = Route::currentRouteName();
            $viewName = app('request')->route()->getName();
            $pageTitle = trans('navigation.' . $viewName);

            return view($viewName, compact('pageTitle'));
        }
    }
