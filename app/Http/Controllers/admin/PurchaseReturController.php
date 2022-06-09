<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Cli\Invoker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PurchaseReturController extends Controller
{
    //
    function index(Request $request)
    {
        $content_data = array();

        $searchTerm = $request->input('search');

        $purchase_retur = DB::table('purchase_retur')
            ->selectRaw("purchase_retur.*,supplier.*")
            ->orderByDesc('id_purchase_retur')
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase_retur.id_supplier')
            ->join('purchase', 'purchase.id_purchase', '=', 'purchase_retur.id_purchase')
            ->whereRaw(
                "purchase_retur.deleted=0 and 
                (
                purchase_retur.kode_retur like ? 
                or purchase_retur.tanggal like ? 
                or `supplier`.`nama_suplier` like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]
            )
            ->paginate(5);

        $content_data['purchase_retur'] = $purchase_retur;
        $content = view("admin_purchase_retur.purchase_retur", $content_data);
        $breadcrumb = view('admin_purchase_retur.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase Retur";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function add()
    {
        $form = new stdClass();

        $content_data = array();
        $content_data['form'] = $form;


        $supplier = DB::table('supplier')
            ->where('deleted', 0)
            ->orderByDesc('id_supplier')
            ->get();

        $content_data['opt_supplier'] = $supplier->toJson();

        // dd($supplier->toJson());


        $content = view("admin_purchase_retur.purchase_retur_add", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function purchase_dtt(Request $request)
    {

        // print_r2($_REQUEST);

        $searchTerm = '';
        $skip = intval($request->input('start'));
        $take = intval($request->input('length'));

        $search = $request->input('search');
        if (isset($search['value'])) {
            $searchTerm = $search['value'];
        }

        $purchase = DB::table('purchase')
            ->orderByDesc('id_purchase')
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase.id_supplier')
            ->whereRaw(
                "purchase.deleted=0 and 
                (kode_purchase like ? 
                or tanggal like ? 
                or `supplier`.`nama_suplier` like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]
            )
            ->skip($skip)->take($take)
            ->get()->toArray();

        // dd($purchase);

        $res = array();
        foreach ($purchase as $row) {
            $buff = array();

            $buttons = '
            <span id_purchase="' . $row->id_purchase . '" class="btn btn-sm btn-primary pilih_purchase" >pilih</span>
            ';

            $buff[] = $buttons;
            $buff[] = $row->kode_purchase;
            $buff[] = $row->nama_suplier;
            $buff[] = $row->tanggal;
            $buff[] = $row->sub;
            $buff[] = $row->pajak;
            $buff[] = $row->total;

            array_push($res, $buff);
        }

        // dd($res);

        $response = array(
            'data' => $res,
            "recordsTotal" => 100,
            "recordsFiltered" => 100,
        );

        return response()->json($response);
    }

    function purchase_detail($id)
    {
        $purchase = DB::table('purchase')
            ->orderByDesc('id_purchase')
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase.id_supplier')
            ->where('purchase.id_purchase', $id)
            ->first();

        $purchase_arr = (array)$purchase;

        $purchase_detail = DB::table('purchase_detail')
            ->leftJoin('master_item', 'master_item.id_item', '=', 'purchase_detail.id_item')
            ->where('id_purchase', $id)->get()->toArray();

        $purchase_detail_arr = array();
        foreach ($purchase_detail as $row) {
            $buff = (array) $row;

            array_push($purchase_detail_arr, $buff);
        }

        $response = array(
            'purchase' => $purchase_arr,
            'purchase_detail' => $purchase_detail_arr
        );

        return response()->json($response);
    }
}
