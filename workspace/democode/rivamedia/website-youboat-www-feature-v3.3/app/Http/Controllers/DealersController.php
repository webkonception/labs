<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DealersCaractsRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchController as Search;

use App\DealersCaracts;
use App\Countries;
use Cache;

class DealersController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request)
    {
        $viewName       = app('request')->route()->getName();
        if ($request) {
            try {
                $return = [];
                $datasRequest = $request->all();
                $routeParameters = $request->route()->parameters();
                $country_code = $routeParameters['country_code'];
                session()->put('country_code', $country_code);
                $rewrite_url = $routeParameters['rewrite_url'];
                $countries = Countries::orderBy("name", "asc")->pluck('name', 'id')->all();

                $dealer_caracts = [];

                $usercaracts = DealersCaracts::where('rewrite_url', $rewrite_url)->get();

                $dealer_caracts = [];
                if (isset($usercaracts)) {
                    $array = json_decode(json_encode($usercaracts), true);
                    if (is_array($array) && !empty($array[0])) {
                        $dealer_caracts = $array[0];
                        $ads_list = Search::getAdsList(['dealerscaracts_id' =>$dealer_caracts['id'], 'countries_id' =>$dealer_caracts['country_id']]);
                        $return = compact('country_code', 'dealer_caracts', 'countries', 'ads_list');
                        return view($viewName, $return);
                    }
                }
                abort(404);
            } catch(\Exception $e) {
                //var_dump($e->getMessage());
                return redirect()->back()->withErrors($e->getMessage());
            }
        } else {
            return view($viewName);
        }
    }
}
