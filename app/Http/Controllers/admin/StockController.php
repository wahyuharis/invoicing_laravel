<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class StockController extends Controller
{
    //
    function index(Request $request)
    {
        $content_data = array();

        $searchTerm = $request->input('search');

        $item_stock = DB::table('master_item')
            ->selectRaw("master_item.`id_item`,
                master_item.`kode_item`,
                master_item.nama_item,
                master_category.nama_category,
                (SELECT stock.qty_akhir from stock
                WHERE stock.id_item=master_item.id_item
                ORDER by stock.id_stock desc
                limit 1) as qty")
            ->leftJoin('master_category', 'master_category.id_category', '=', 'master_item.id_category')
            ->whereRaw("
            master_item.deleted=0
            and
            (
                master_item.`kode_item` like ? or
                master_item.`nama_item` like ? or
                master_category.nama_category like ?
            )
            ", ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"])
            ->orderByDesc('id_item')
            ->paginate(5);

        $content_data['item_stock'] = $item_stock;

        $layout_data = array();
        $layout_data['page_title'] = "Stock";
        $layout_data['content'] = view('admin_stock.stock', $content_data);
        $layout_data['breadcrumb'] = view('admin_stock.breadcrumb');

        return view('admin.layout', $layout_data);
    }
    function stock_detail($id_item)
    {
        $item_stock_detail = array();

        $item_stock_detail = DB::table('stock')
            ->join('master_item', 'master_item.id_item', '=', 'stock.id_item')
            ->where('stock.id_item', $id_item)
            ->paginate(5);


        // dd($item_stock_detail->toArray());

        $content_data['item_stock_detail'] = $item_stock_detail;

        $layout_data = array();
        $layout_data['page_title'] = "Stock Detail";
        $layout_data['content'] = view('admin_stock.stock_detail', $content_data);
        $layout_data['breadcrumb'] = view('admin_stock.breadcrumb');

        return view('admin.layout', $layout_data);
    }

    function adj()
    {

        $content_data = array();

        $layout_data = array();
        $layout_data['page_title'] = "Stock Adj";
        $layout_data['content'] = view('admin_stock.stock_adj', $content_data);
        $layout_data['breadcrumb'] = view('admin_stock.breadcrumb');

        return view('admin.layout', $layout_data);
    }

    function adj_submit(Request $request)
    {
        $success = true;
        $data = [];
        $message = "";

        $ko_output = $request->input('ko_output');
        $ko_array = json_decode($ko_output, true);

        // dd($ko_array);

        if (!is_array($ko_array['item_list']) && count($ko_array['item_list'])) {
            $success = false;
            $message .= "Maaf item masih kosong";
        }

        if ($success) {
            $item_list = $ko_array['item_list'];

            foreach ($item_list as $row) {

                $insert=array();
                $insert['id_item'] = $row['id_item'];
                $insert['qty_awal'] = floatval2($row['qty']);
                $insert['qty_adj'] = floatval2($row['qty_adj']);
                $insert['qty_akhir'] = floatval2($row['qty_adj']);
                $db = DB::table('stock')->insert($insert);
            }
        }




        $res = array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );
        return response()->json($res);
    }
}
