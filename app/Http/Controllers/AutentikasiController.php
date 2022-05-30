<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutentikasiController extends Controller
{
    //
    function index()
    {
        return view('login');
    }

    function login_submit(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');
        $password = md5($password);

        // dd($_POST);
        // DB::enableQueryLog();
        $user = DB::table('user')
            ->where('email', '=', $email)
            ->where('password', '=', $password)
            ->get()->toArray();
        // dd(DB::getQueryLog());



        if (count($user) > 0) {
            $user2 =  (array)  $user[0];
            session($user2);
            return redirect('admin/category');
        } else {
            return redirect('login');
        }
    }

    function logout(Request $request)
    {

        $request->session()->flush();
        return redirect('login');
    }
}
