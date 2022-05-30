<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    //
    function index()
    {
        $content = '';

        $layout_data = array();
        $layout_data['page_title'] = "Home";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = '';

        return view('admin.layout', $layout_data);
    }
}
