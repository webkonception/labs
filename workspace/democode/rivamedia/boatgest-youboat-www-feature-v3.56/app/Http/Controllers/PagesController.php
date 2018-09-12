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
        //$this->middleware('auth');
    }

    /**
     * Show the application scrap.
     *
     * @return \Illuminate\Http\Response
     */
    public function scrap()
    {
        return view('scrap');
    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Show the application about.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('about');
    }
}
