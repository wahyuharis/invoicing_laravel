<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class ItemController extends Controller
{
    //
    function index(Request $request)
    {

        $searchTerm = $request->input('search');

        $master_item = DB::table('master_item')
            ->leftJoin('master_category', 'master_category.id_category', '=', 'master_item.id_category')
            ->orderByDesc('id_item')
            ->whereRaw("master_item.deleted=0 
            and (
                nama_item like ? 
                or kode_item like ?
                or  master_category.nama_category like ?
                )", ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"])

            ->paginate(5);



        $content_data = array();
        $content_data['master_item'] = $master_item;
        $content = view("admin_item.master_item", $content_data);
        $breadcrumb = view('admin_item.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Master Item";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit($id = '')
    {
        $form = array();

        $form = new stdClass();
        $form->id = '';
        $form->nama_item = '';
        $form->kode_item = '';
        $form->id_category = '';
        $form->satuan_item = '';
        $form->harga_beli = '';
        $form->harga_jual = '';
        $form->foto1 = '';

        $db = DB::table('master_item')->where('id_item', $id)
            ->first();

        $db_category = DB::table('master_category')->get();
        $opt_category = array();
        $opt_category[''] = "-- Pilih Category --";

        foreach ($db_category as $row) {
            $opt_category[$row->id_category] = $row->nama_category;
        }

        if ($db) {
            $form->id = $id;
            $form->nama_item =  $db->nama_item;
            $form->kode_item =  $db->kode_item;
            $form->id_category = $db->id_category;
            $form->satuan_item =  $db->satuan_item;
            $form->harga_beli = $db->harga_beli;
            $form->harga_jual =  $db->harga_jual;
            $form->foto1 = $db->foto1;
        }

        $content_data = array();
        $content_data['form'] = $form;
        $content_data['opt_category'] = $opt_category;

        $content = view("admin_item.item_edit", $content_data);
        $breadcrumb = view('admin_item.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Master Item";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function submit(Request $request)
    {
        $success = false;
        $data = [];
        $message = "";

        $validation_rule = array();
        if (empty(trim($request->input('id')))) {
            $validation_rule['nama_item'] = 'required';
            $validation_rule['id_category'] = 'required';
            $validation_rule['satuan_item'] = 'required';
            $validation_rule['harga_beli'] = 'required';
            $validation_rule['harga_jual'] = 'required';
        } else {
            $validation_rule['nama_item'] = 'required';
            $validation_rule['id_category'] = 'required';
            $validation_rule['satuan_item'] = 'required';
            $validation_rule['harga_beli'] = 'required';
            $validation_rule['harga_jual'] = 'required';
        }

        // dd($this->generate_code());

        $validator = Validator::make($request->all(), $validation_rule);

        if ($validator->passes()) {
            $success = true;
        } else {
            $success = false;
            $err = $validator->errors()->all();

            foreach ($err as $err_msg) {
                $message .= '' . $err_msg . "<br>";
            }
        }


        $file1 = $request->file('file1');
        $newname = "";
        if ($file1) {
            $newname = "img-" . uniqid() . "." . $file1->getClientOriginalExtension();
            $file1->move('upload/', $newname);
        }

        if ($success) {


            $id = trim($request->input('id'));
            if (empty(trim($id))) {

                if (empty($request->input('kode_item'))) {
                    $insert['kode_item'] = strtoupper(uniqid());
                } else {
                    $insert['kode_item'] = $request->input('kode_item');
                }



                $insert['foto1'] = $newname;
                $insert['nama_item'] = $request->input('nama_item');
                $insert['id_category'] = $request->input('id_category');
                $insert['satuan_item'] = $request->input('satuan_item');
                $insert['harga_beli'] = $request->input('harga_beli');
                $insert['harga_jual'] = $request->input('harga_jual');

                $db = DB::table('master_item')
                    ->insert($insert);

                $insert_id = DB::getPdo()->lastInsertId();
            } else {

                if (empty($request->input('kode_item'))) {
                    $insert['kode_item'] = strtoupper(uniqid());
                } else {
                    $insert['kode_item'] = $request->input('kode_item');
                }

                $insert['foto1'] = $newname;
                $insert['nama_item'] = $request->input('nama_item');
                $insert['id_category'] = $request->input('id_category');
                $insert['satuan_item'] = $request->input('satuan_item');
                $insert['harga_beli'] = $request->input('harga_beli');
                $insert['harga_jual'] = $request->input('harga_jual');

                $affected = DB::table('master_item')
                    ->where('id_item', $id)
                    ->update($insert);
            }
        }

        $res = array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );

        return response()->json($res);
    }

    function datatables()
    {
        $res = array();
        $res['data'] = array();

        $db = DB::table('master_item')
            ->leftJoin('master_category', 'master_category.id_category', '=', 'master_item.id_category')
            ->orderByDesc('id_item')
            ->get();

        $dbres = array();
        foreach ($db as $row) {
            $buff = array();
            // $buff[] = $row->id_item;

            $buttons = '
            <span id_item="' . $row->id_item . '" class="btn btn-sm btn-primary pilih_item" >pilih</span>
            ';

            $buff[] = $buttons;
            $buff[] = $row->kode_item;
            $buff[] = $row->nama_item;
            $buff[] = $row->nama_category;
            $buff[] = $row->satuan_item;

            // number_format()

            $buff[] = number_format($row->harga_beli, 2);

            array_push($dbres, $buff);
        }

        $res['data'] = $dbres;

        return response()->json($res);
    }

    function get_item($id)
    {
        $db = DB::table('master_item')->where('id_item', '=', $id)
            ->first();

        $db2 = DB::table('stock')->where('id_item', '=', $id)->orderByDesc('id_stock')
            ->limit(1)
            ->get()->toArray();

        $db->qty_akhir = 0;
        if (count($db2) > 0) {
            $db->qty_akhir=$db2[0]->qty_akhir;
        }

        $res = (array) $db;

        return response()->json($res);
    }

    function delete($id = '')
    {
        $db = DB::table('master_item')->where('id_item', $id)
            ->update(['deleted' => 1]);

        $prev = url()->previous();

        return redirect($prev);
    }
}
