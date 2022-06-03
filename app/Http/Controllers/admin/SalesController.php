<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSalesModel;
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

    function submit(Request $request)
    {
        // require_once ('haris_helper.php');
        $success = true;
        $data = [];
        $message = "";

        $ko_output = $request->input('ko_output');
        $ko_array = json_decode($ko_output, true);

        $item_list = $ko_array['item_list'];

        if (!isset($ko_array['id_customer'])) {
            $success = false;
            $message .= "Maaf Customer wajib di isi<br>";
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

        if (empty(trim($ko_array['kode_sales']))) {
            $ko_array['kode_sales'] = strtoupper(uniqid());
        }

        // print_r2($ko_array);

        if ($success) {

            DB::beginTransaction();


            $insert = array();
            $insert['kode_sales'] = trim($ko_array['kode_sales']);
            $insert['tanggal'] = trim($ko_array['tanggal']);
            $insert['pajak'] = trim($ko_array['pajak']);
            $insert['id_customer'] = trim($ko_array['id_customer']);
            $insert['sub'] = floatval2($ko_array['sub']);
            $insert['total'] = floatval2($ko_array['total']);
            $insert['barang_dikirim'] = floatval2($ko_array['barang_dikirim']);
            $insert['catatan'] = trim($ko_array['catatan']);

            $db0 = DB::table('sales')->insert($insert);
            $insert_id = DB::getPdo()->lastInsertId();
            $data['insert_id'] = $insert_id;

            $insert2 = array();
            foreach ($item_list as $row) {
                $insert2['id_sales'] = $insert_id;
                $insert2['id_item'] = $row['id_item'];
                $insert2['harga'] = floatval2($row['harga_jual']);
                $insert2['qty'] = floatval2($row['qty']);
                $insert2['disc'] = floatval2($row['disc']);
                $insert2['sub'] = floatval2($row['sub']);

                $db1 = DB::table('sales_detail')->insert($insert2);
            }

            $insert3 = array();
            if (floatval2($ko_array['jml_dibayar']) > 0) {
                $insert3['keperluan'] = "salesing";
                $insert3['tabel'] = "sales";
                $insert3['id_tabel'] = $insert_id;
                $insert3['total'] = floatval2($ko_array['jml_dibayar']);
                $insert3['keterangan'] = "Penjualan Barang";

                $db2 = DB::table('cashflow')->insert($insert3);
            }

            if ($ko_array['barang_dikirim'] == 1) {
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
                    $insert4['qty_out'] = floatval2($row4['qty']);
                    $insert4['qty_akhir'] = $qty_akhir;

                    // dd($insert4);

                    $db4 = DB::table('stock')->insert($insert4);
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

    function edit($id = '')
    {
        $form = new stdClass();

        $content_data = array();

        $sales = DB::table('sales')
            ->leftJoin('customer', 'customer.id_customer', '=', 'sales.id_customer')
            ->where('id_sales', $id)
            ->first();

        $sales_list = DB::table('sales_detail')
            ->leftJoin('master_item', 'master_item.id_item', '=', 'sales_detail.id_item')
            ->where('id_sales', $id)
            ->get();

        $cashflow = DB::table('cashflow')
            ->where('tabel', 'sales')
            ->where('id_tabel', $id)
            ->first();

        $cashflow2 = new stdClass();
        $cashflow2->total = "0";
        if ($cashflow) {
            $cashflow2->total = $cashflow->total;
        }


        $sales_model = new AdminSalesModel();

        // dd($purchase_model->get_sisa_tagihan($id));
        // dd($purchase);

        $sisa_tagihan = $sales->total;
        if (count($sales_model->get_sisa_tagihan($id)) > 0) {
            $sisa_tagihan = $sales_model->get_sisa_tagihan($id)[0]->sisa_tagihan;
        }

        $content_data['sales'] = $sales;
        $content_data['sales_list'] = $sales_list;
        $content_data['cashflow'] = $cashflow2;
        $content_data['sisa_tagihan'] = $sisa_tagihan;
        $content_data['id'] = $id;

        $content = view("admin_sales.sales_edit", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Sales";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit_submit(Request $request)
    {
        $success = true;
        $data = [];
        $message = "";


        // dd($request->all());

        $id = $request->input('id');
        $barang_dikirim = $request->input('barang_dikirim');
        $jml_dibayar = $request->input('jml_dibayar');

        DB::beginTransaction();

        $db0 = DB::table('sales')->where('id_sales', $id)->first();

        // if( intval($db0->barang_diterima) ){

        // }

        if (intval($barang_dikirim) > 0 && intval($db0->barang_dikirim) < 1) {
            $db = DB::table('sales_detail')->where('id_sales', $id)->get()->toArray();
            foreach ($db as $row) {

                $db3 = DB::table('stock')
                    ->where('id_item', $row->id_item)
                    ->orderByDesc('id_stock')
                    ->limit(1)
                    ->get();
                $qty_akhir = 0;
                $qty_awal = 0;

                if (count($db3) > 0) {
                    $db3arr = $db3->toArray();

                    $qty_awal = $db3arr[0]->qty_akhir;

                    $qty_akhir = $qty_awal - floatval2($row->qty);
                } else {
                    $qty_akhir = $qty_akhir - floatval2($row->qty);
                }


                $insert4['id_item'] = $row->id_item;
                $insert4['qty_awal'] = floatval2($qty_awal);
                $insert4['qty_out'] = floatval2($row->qty);
                $insert4['qty_akhir'] = $qty_akhir;

                $db4 = DB::table('stock')->insert($insert4);
            }

            $db5 = DB::table('sales')->where('id_sales', $id)->update(['barang_dikirim' => 1]);
        }

        $insert3 = array();
        if (floatval2($jml_dibayar) > 0) {
            $insert3['keperluan'] = "salesing";
            $insert3['tabel'] = "sales";
            $insert3['id_tabel'] = $id;
            $insert3['total'] = floatval2($jml_dibayar);
            $insert3['keterangan'] = "Penjualan Barang";

            $db2 = DB::table('cashflow')->insert($insert3);
        }

        $data['insert_id'] = $id;
        DB::commit();

        $res = array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );
        return response()->json($res);
    }
}
