<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPurchaseModel;
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
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase.id_supplier')
            ->whereRaw(
                "purchase.deleted=0 and 
                (kode_purchase like ? 
                or tanggal like ? 
                or `supplier`.`nama_suplier` like ? )",
                ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]
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
            $insert['catatan'] = trim($ko_array['catatan']);

            $db0 = DB::table('purchase')->insert($insert);
            $insert_id = DB::getPdo()->lastInsertId();
            $data['insert_id'] = $insert_id;

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
                        ->limit(1)
                        ->get();

                    $qty_akhir = 0;
                    $qty_awal = 0;
                    if (count($db3) > 0) {
                        $db3arr = $db3->toArray();

                        $qty_awal = $db3arr[0]->qty_akhir;

                        $qty_akhir = $qty_awal + floatval2($row4['qty']);
                    } else {
                        $qty_akhir = $qty_akhir + floatval2($row4['qty']);
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

        $purchase = DB::table('purchase')
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase.id_supplier')
            ->where('id_purchase', $id)
            ->first();

        $purchase_list = DB::table('purchase_detail')
            ->leftJoin('master_item', 'master_item.id_item', '=', 'purchase_detail.id_item')
            ->where('id_purchase', $id)
            ->get();

        $cashflow = DB::table('cashflow')
            ->where('tabel', 'purchase')
            ->where('id_tabel', $id)
            ->first();

        $cashflow2 = new stdClass();
        $cashflow2->total = "0";
        if ($cashflow) {
            $cashflow2->total = $cashflow->total;
        }


        $purchase_model = new AdminPurchaseModel();

        // dd($purchase_model->get_sisa_tagihan($id));
        // dd($purchase);

        $sisa_tagihan = $purchase->total;
        if (count($purchase_model->get_sisa_tagihan($id)) > 0) {
            $sisa_tagihan = $purchase_model->get_sisa_tagihan($id)[0]->sisa_tagihan;
        }

        $content_data['purchase'] = $purchase;
        $content_data['purchase_list'] = $purchase_list;
        $content_data['cashflow'] = $cashflow2;
        $content_data['sisa_tagihan'] = $sisa_tagihan;
        $content_data['id'] = $id;

        $content = view("admin_purchase.purchase_edit", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit_submit(Request $request)
    {
        $success = true;
        $data = [];
        $message = "";


        // dd($_POST);

        $id = $request->input('id');
        $barang_diterima = $request->input('barang_diterima');
        $jml_dibayar = $request->input('jml_dibayar');

        DB::beginTransaction();

        $db0 = DB::table('purchase')->where('id_purchase', $id)->first();

        // if( intval($db0->barang_diterima) ){

        // }

        if (intval($barang_diterima) > 0 && intval($db0->barang_diterima) < 1) {
            $db = DB::table('purchase_detail')->where('id_purchase', $id)->get()->toArray();
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

                    $qty_akhir = $qty_awal + floatval2($row->qty);
                } else {
                    $qty_akhir = $qty_akhir + floatval2($row->qty);
                }


                $insert4['id_item'] = $row->id_item;
                $insert4['qty_awal'] = floatval2($qty_awal);
                $insert4['qty_in'] = floatval2($row->qty);
                $insert4['qty_akhir'] = $qty_akhir;

                $db4 = DB::table('stock')->insert($insert4);
            }

            $db5 = DB::table('purchase')->where('id_purchase', $id)->update(['barang_diterima' => 1]);
        }

        $insert3 = array();
        if (floatval2($jml_dibayar) > 0) {
            $insert3['keperluan'] = "purchasing";
            $insert3['tabel'] = "purchase";
            $insert3['id_tabel'] = $id;
            $insert3['total'] = floatval2($jml_dibayar) * (-1);
            $insert3['keterangan'] = "Pembelian Barang";

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

    function view($id)
    {
        $form = new stdClass();

        $content_data = array();

        $purchase = DB::table('purchase')
            ->leftJoin('supplier', 'supplier.id_supplier', '=', 'purchase.id_supplier')
            ->where('id_purchase', $id)
            ->first();

        $purchase_list = DB::table('purchase_detail')
            ->leftJoin('master_item', 'master_item.id_item', '=', 'purchase_detail.id_item')
            ->where('id_purchase', $id)
            ->get();

        $cashflow = DB::table('cashflow')
            ->where('tabel', 'purchase')
            ->where('id_tabel', $id)
            ->first();

        $cashflow2 = new stdClass();
        $cashflow2->total = "0";
        if ($cashflow) {
            $cashflow2->total = $cashflow->total;
        }


        $purchase_model = new AdminPurchaseModel();

        // dd($purchase_model->get_sisa_tagihan($id));
        // dd($purchase);

        $sisa_tagihan = $purchase->total;
        if (count($purchase_model->get_sisa_tagihan($id)) > 0) {
            $sisa_tagihan = $purchase_model->get_sisa_tagihan($id)[0]->sisa_tagihan;
        }

        $content_data['purchase'] = $purchase;
        $content_data['purchase_list'] = $purchase_list;
        $content_data['cashflow'] = $cashflow2;
        $content_data['sisa_tagihan'] = $sisa_tagihan;
        $content_data['id'] = $id;

        $content = view("admin_purchase.purchase_view", $content_data);
        $breadcrumb = view('admin_purchase.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Purchase";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function delete($id = '')
    {
        DB::beginTransaction();
        $purchase = DB::table('purchase')->where('id_purchase', '=', $id)->get()->first();
        $purchase_list = DB::table('purchase_detail')->where('id_purchase', '=', $id)->get()->toArray();

        // dd($purchase);

        if ($purchase->barang_diterima > 0) {
            foreach ($purchase_list as $row) {

                $db3 = DB::table('stock')
                    ->where('id_item', $row->id_item)
                    ->orderByDesc('id_stock')
                    ->first();

                $qty_akhir = 0;
                $qty_awal = 0;
                if ($db3) {
                    $qty_awal = $db3->qty_akhir;
                    $qty_akhir = $db3->qty_akhir - floatval2($row->qty);;
                }


                $insert4['id_item'] = $row->id_item;
                $insert4['qty_awal'] = $qty_awal;
                $insert4['qty_out'] = floatval2($row->qty);
                $insert4['qty_akhir'] = $qty_akhir;
                $insert4['keterangan'] = "Batal Transaksi";

                $db4 = DB::table('stock')->insert($insert4);
            }
        }


        $purchase = DB::table('purchase')->where(['id_purchase' => $id])->delete();
        $purchase_list=DB::table('purchase_detail')->where(['id_purchase' => $id])->delete();
        $cashflow = DB::table('cashflow')
            ->where([
                'id_tabel' => $id,
                'tabel' => 'purchase',
            ])->delete();
        DB::commit();

        $prev = url()->previous();

        return redirect($prev);
    }
}
