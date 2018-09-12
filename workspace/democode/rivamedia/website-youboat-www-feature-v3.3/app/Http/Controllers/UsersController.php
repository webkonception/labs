<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

class UsersController extends Controller
{

    /**
     * logout and reset password
     *
     * @param         $email
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutResetPassword($email)
    {
        Auth::logout();
        $data['logout'] = true;
        Session::flush();
        Cache::flush();
        return view('auth.passwords.email', ['email'=>$email]);
    }

}
