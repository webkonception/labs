<?php

namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Enquiry;
//use App\BodCaracts;
//use App\ContactForm;
//use App\ProspectiveCustomers;

use App\CustomersCaracts;
use App\CommercialsCaracts;
use App\DealersCaracts;
use App\PrivatesCaracts;
use App\Http\Requests\UpdateCustomersCaractsRequest;
use App\Http\Requests\UpdateCommercialsCaractsRequest;
use App\Http\Requests\UpdateDealersCaractsRequest;
use App\Http\Requests\UpdatePrivatesCaractsRequest;

use App\Roles;
use App\Countries;

use App\Role;
use App\User;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use QueryException;

use Auth;
//use DB;
//use Carbon\Carbon;
//use ConsoleTVs\Charts\Facades\Charts as Charts;
//use Spatie\LaravelAnalytics\LaravelAnalyticsFacade as LaravelAnalytics;
//use App\Http\Controllers\boatgestAdmin\StatisticsController as StatisticsController;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index (Request $request) {
        $viewName = app('request')->route()->getName();
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user_type = $user->type;
        switch($user_type) {
            case 'admin':
                $usercaracts = [];
                break;
            case 'private':
                $usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
                break;
            case 'dealer':
                $usercaracts = DealersCaracts::where('user_id', $user_id)->get();
                break;
            case 'customer':
                $usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
                break;
            case 'commercial':
                $usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
                break;
        }
        $array = json_decode(json_encode($usercaracts), true);
        //asort($array);
        if(!empty($array[0])) {
            $user_caracts = $array[0];
        }

        $countries = Countries::orderBy("name", "asc")->pluck('name','id')->all();
        return view($viewName, compact('user', 'user_caracts', 'countries'));
    }
}
