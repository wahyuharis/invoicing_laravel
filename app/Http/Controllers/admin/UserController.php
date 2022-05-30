<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    function index()
    {

        $content =  view('admin.password');

        $layout_data = array();
        $layout_data['page_title'] = "Ubah Password";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = '';

        return view('admin.layout', $layout_data);
    }

    function submit(Request $request)
    {
        $success = false;
        $data = [];
        $message = "";

        $password = $request->input('password');
        $password2 = $request->input('password2');

        if (strlen($password) >= 5) {
            $success = true;
        } else {
            $success = false;
            $message .= "Maaf Password Minimal 5 Karakter <br>";
        }

        if ($password == $password2) {
            $success = true;
        } else {
            $success = false;
            $message .= "Maaf Password Harus Sama<br>";
        }

        if ($success) {
            $username = $request->session()->get('username');

            $update = array(
                'password' => md5($request->input('password'))
            );

            $db = DB::table('user')
                ->where('username', '=', $username)
                ->update($update);
        }

        $res = array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );
        return response()->json($res);
    }
}
