<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Cli\Invoker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PurchaseReturController extends Controller
{

    private $judul = "Purchase Retur";

    //
    function index(Request $request)
    {
        $content_data = array();

        $searchTerm = $request->input('search');

        $purchase_retur = DB::table('purchase_retur')
            ->selectRaw("purchase_retur.*,supplier.*,purchase.kode_purchase")
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
        $layout_data['page_title'] = $this->judul;
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
        $layout_data['page_title'] = "Buat " . $this->judul;
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

    function submit(Request $request)
    {
        $message = "";
        $success = true;
        $data = [];

        $ko_output = $request->input('ko_output');
        $ko_array = json_decode($ko_output, true);


        if (empty(trim($ko_array['kode_purchase_retur']))) {
            $ko_array['kode_purchase_retur'] = "PR-" . strtoupper(uniqid());
        }
        // dd($ko_array);

        if (empty(trim($ko_array['id_purchase']))) {
            $success = false;
            $message .= "<p>Maaf Harus memilih kode Purchase Yang Diretur</p>";
        }

        if (empty(trim($ko_array['tanggal']))) {
            $success = false;
            $message .= "<p>Maaf Tanggal Pembelian Kosong</p>";
        }

        if (empty(trim($ko_array['tanggal_retur']))) {
            $success = false;
            $message .= "<p>Maaf Tanggal Retur Tidak Boleh Kosong</p>";
        }

        if (empty(trim($ko_array['id_supplier']))) {
            $success = false;
            $message .= "<p>Maaf Supplier Tidak Boleh Kosong</p>";
        }

        if (!is_array($ko_array['item_list'])) {
            $success = false;
            $message .= "<p>Maaf Terjadi Kesalahan</p>";
        }

        if (is_array($ko_array['item_list']) && count($ko_array['item_list']) < 1) {
            $success = false;
            $message .= "<p>Maaf Daftar Item Tidak Boleh Kosong</p>";
        }

        if ($success) {

            DB::beginTransaction();

            $insert['id_purchase'] = trim($ko_array['id_purchase']);
            $insert['kode_retur'] = trim($ko_array['kode_purchase_retur']);
            $insert['id_supplier'] = trim($ko_array['id_supplier']);
            $insert['tanggal'] = trim($ko_array['tanggal_retur']);
            $insert['sub'] = floatval2($ko_array['sub']);
            $insert['pajak'] = floatval2($ko_array['pajak']);
            $insert['total'] = floatval2($ko_array['total']);
            $insert['catatan'] = trim($ko_array['catatan']);
            $insert['barang_dikembalikan'] = trim($ko_array['barang_diterima']);

            $db = DB::table('purchase_retur')->insert($insert);
            $insert_id = DB::getPdo()->lastInsertId();
            $data['insert_id'] = $insert_id;

            $item_list = $ko_array['item_list'];

            $insert2 = array();
            $insert2['id_purchase_retur'] = $insert_id;
            foreach ($item_list as $row) {
                $insert2['id_purchase_retur'] = $insert_id;
                $insert2['id_item'] = $row['id_item'];
                $insert2['harga'] = floatval2($row['harga_beli']);
                $insert2['qty'] = floatval2($row['qty']);
                $insert2['disc'] = floatval2($row['disc']);
                $insert2['sub'] = floatval2($row['sub']);

                $db1 = DB::table('purchase_retur_detail')->insert($insert2);
            }

            // $insert3 = array();
            // if (floatval2($ko_array['total']) > 0) {
            //     $insert3['keperluan'] = "purchase retur";
            //     $insert3['tabel'] = "purchase";
            //     $insert3['id_tabel'] = trim($ko_array['id_purchase']);
            //     $insert3['total'] = floatval2($ko_array['total']) * (-1);
            //     $insert3['keterangan'] = "Retur Pembelian Barang";

            //     $db2 = DB::table('cashflow')->insert($insert3);
            // }

            if ($ko_array['barang_diterima'] == 1) {
                $insert4 = array();
                foreach ($item_list as $row4) {

                    $db3 = DB::table('stock')
                        ->where('id_item', $row4['id_item'])
                        ->orderByDesc('id_stock')
                        ->limit(1)
                        ->get();

                    $qty_akhir = 0;
                    $qty_awal = 0;
                    if (count($db3) > 0) {
                        $db3arr = $db3->toArray();

                        $qty_awal = $db3arr[0]->qty_akhir;

                        $qty_akhir = $qty_awal - floatval2($row4['qty']);
                    } else {
                        $qty_akhir = $qty_akhir - floatval2($row4['qty']);
                    }


                    $insert4['id_item'] = $row4['id_item'];
                    $insert4['qty_awal'] = floatval2($qty_awal);
                    $insert4['qty_in'] = floatval2($row4['qty']);
                    $insert4['qty_akhir'] = $qty_akhir;

                    // dd($insert4);

                    $db4 = DB::table('stock')->insert($insert4);
                }
            }
            DB::commit();

        }

        $res = array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        );

        return response()->json($res);
    }
}
