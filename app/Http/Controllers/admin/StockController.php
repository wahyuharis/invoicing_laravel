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
            ",["%{$searchTerm}%","%{$searchTerm}%","%{$searchTerm}%"])
            ->orderByDesc('id_item')
            ->paginate(5);

        $content_data['item_stock'] = $item_stock;

        $layout_data = array();
        $layout_data['page_title'] = "Stock";
        $layout_data['content'] = view('admin_stock.stock', $content_data);
        $layout_data['breadcrumb'] = view('admin_stock.breadcrumb');

        return view('admin.layout', $layout_data);
    }
}
