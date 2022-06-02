<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminCashflowModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;


class CashflowController extends Controller
{
    //
    function index(Request $request)
    {
        $content_data = array();
        $searchTerm = $request->input('search');

        $cashflow_model=new AdminCashflowModel();

        $cashflow = $cashflow_model->get_list();
        $content_data['cashflow'] = $cashflow;

        // dd($cashflow);

        $layout_data = array();
        $layout_data['page_title'] = "Cashflow";
        $layout_data['content'] = view('admin_cashflow.cashflow', $content_data);
        $layout_data['breadcrumb'] = view('admin_cashflow.breadcrumb');

        return view('admin.layout', $layout_data);
    }
}
