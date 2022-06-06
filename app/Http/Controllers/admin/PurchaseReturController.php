<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                purchase.kode_purchase like ? 
                or purchase_retur.tanggal like ? 
                or `supplier`.`nama_suplier` like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]
            )
            ->paginate(5);

        $content_data['purchase_retur'] = $purchase_retur;
        $content = view("admin_purchase_retur.purchase_retur", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase Retur";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }
}
