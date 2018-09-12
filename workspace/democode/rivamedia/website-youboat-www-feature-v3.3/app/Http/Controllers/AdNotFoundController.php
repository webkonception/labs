<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdNotFoundController extends Controller
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
        ////////////////////////////////////////////////////////////////////////
        // Prevent ERROR 500
        // FastCGI: comm with server "/fcgi-bin-php5-fpm" aborted: read failed
        ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
        ////ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
        //ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
        ////ini_set('memory_limit', '256M'); // Maximum amount of memory a script may consume (128MB by default)
        //ini_set('memory_limit', '-1');
        set_time_limit (0);
        ////////////////////////////////////////////////////////////////////////

        $viewName       = 'errors.ad_not_found';
        if ($request) {
            //return view($viewName, $request);
            return response()->view($viewName, $request, '301');
        } else {
            return view($viewName);
        }
    }
}
