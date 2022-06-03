<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class SalesController extends Controller
{
    //
    function index(Request $request)
    {
        $searchTerm = $request->input('search');

        $sales = DB::table('sales')
            ->orderByDesc('id_sales')
            ->leftJoin('customer', 'customer.id_customer', '=', 'sales.id_customer')
            ->whereRaw(
                "sales.deleted=0 and 
            (kode_sales like ? 
            or tanggal like ? 
            or `customer`.`nama_customer` like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]
            )
            ->paginate(5);

        $content_data = array();
        $content_data['sales'] = $sales;
        $content = view("admin_sales.sales", $content_data);
        $breadcrumb = view('admin_sales.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Sales";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function add()
    {

        $form = new stdClass();

        $content_data = array();
        $content_data['form'] = $form;


        $customer = DB::table('customer')
            ->where('deleted', 0)
            ->orderByDesc('id_customer')
            ->get();

        $content_data['opt_customer'] = $customer->toJson();

        // dd($customer->toArray());


        $content = view("admin_sales.sales_add", $content_data);
        $breadcrumb = view('admin_sales.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }
}
