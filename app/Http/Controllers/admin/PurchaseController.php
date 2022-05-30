<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

// echo base_path() . '/helper/haris_helper.php';
// die();
class PurchaseController extends Controller
{
    //
    function index(Request $request)
    {

        $searchTerm = $request->input('search');

        $purchase = DB::table('purchase')
            ->orderByDesc('id_purchase')
            ->whereRaw(
                "purchase.deleted=0 and (kode_purchase like ? or tanggal like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%"]
            )
            ->paginate(5);

        $content_data = array();
        $content_data['purchase'] = $purchase;
        $content = view("admin_purchase.purchase", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
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


        $content = view("admin_purchase.purchase_add", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function submit(Request $request)
    {
        // require_once ('haris_helper.php');
        $success = true;
        $data = [];
        $message = "";

        $ko_output = $request->input('ko_output');
        $ko_array = json_decode($ko_output, true);
        // dd($ko_array);

        $item_list = $ko_array['item_list'];

        if (!isset($ko_array['id_supplier'])) {
            $success = false;
            $message .= "Maaf Supplier wajib di isi<br>";
        }


        if (count($item_list) < 1) {
            $success = false;
            $message .= 'Maaf Item Kosong<br>';
        }

        if (count($item_list) > 0) {
            foreach ($item_list as $row) {
                if ($row['qty'] < 1) {
                    $success = false;
                    $message .= 'Maaf Qty Item ' . $row['nama_item'] . ' Kosong<br>';
                }
            }
        }

        // header_json();
        // echo $ko_output;
        // die();

        if (empty(trim($ko_array['kode_purchase']))) {
            $ko_array['kode_purchase'] = strtoupper(uniqid());
        }

        // $qty_akhir = DB::table('stock')
        //     ->where('id_item', 5)
        //     ->orderByDesc('id_stock')
        //     ->first();
        // dd($ko_array);
        // dd($qty_akhir);

        if ($success) {

            DB::beginTransaction();

            $insert = array();
            $insert['kode_purchase'] = trim($ko_array['kode_purchase']);
            $insert['tanggal'] = trim($ko_array['tanggal']);
            $insert['pajak'] = trim($ko_array['pajak']);
            $insert['id_supplier'] = trim($ko_array['id_supplier']);
            $insert['sub'] = floatval2($ko_array['sub']);
            $insert['total'] = floatval2($ko_array['total']);
            $insert['barang_diterima'] = floatval2($ko_array['barang_diterima']);

            $db0 = DB::table('purchase')->insert($insert);
            $insert_id = DB::getPdo()->lastInsertId();

            $insert2 = array();
            foreach ($item_list as $row) {
                $insert2['id_purchase'] = $insert_id;
                $insert2['id_item'] = $row['id_item'];
                $insert2['harga'] = floatval2($row['harga_beli']);
                $insert2['qty'] = floatval2($row['qty']);
                $insert2['disc'] = floatval2($row['disc']);
                $insert2['sub'] = floatval2($row['sub']);

                $db1 = DB::table('purchase_detail')->insert($insert2);
            }

            $insert3 = array();
            if (floatval2($ko_array['jml_dibayar']) > 0) {
                $insert3['keperluan'] = "purchasing";
                $insert3['tabel'] = "purchase";
                $insert3['id_tabel'] = $insert_id;
                $insert3['total'] = floatval2($ko_array['jml_dibayar']) * (-1);
                $insert3['keterangan'] = "Pembelian Barang";

                $db2 = DB::table('cashflow')->insert($insert3);
            }

            if ($ko_array['barang_diterima'] == 1) {
                $insert4 = array();
                foreach ($item_list as $row4) {

                    $db3 = DB::table('stock')
                        ->where('id_item', $row4['id_item'])
                        ->orderByDesc('id_stock')
                        ->first();

                    $qty_akhir = 0;
                    if ($db3) {
                        $qty_akhir = $db3->qty_akhir +  floatval2($row4['qty']);;
                    }


                    $insert4['id_item'] = $row4['id_item'];
                    $insert4['qty_in'] = floatval2($row['qty']);
                    $insert4['qty_akhir'] = $qty_akhir;

                    $db4=DB::table('stock')->insert($insert4);
                }
            }


            DB::commit();
        }


        $res = array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );
        return response()->json($res);
    }
}
