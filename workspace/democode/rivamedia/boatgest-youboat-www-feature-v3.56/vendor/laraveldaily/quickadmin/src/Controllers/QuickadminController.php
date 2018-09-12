<?php
namespace Laraveldaily\Quickadmin\Controllers;

use App\Http\Controllers\Controller;
use Auth;
class QuickadminController extends Controller
{
    /**
     * Show QuickAdmin dashboard page
     *
     * @return Response
     */
    public function index()
    {
        return view(config('quickadmin.route') . '.dashboard');
    }
}