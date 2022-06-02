<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class SupplierController extends Controller
{
    //
    function index(Request $request)
    {
        $searchTerm = $request->input('search');

        $supplier = DB::table('supplier')
            ->selectRaw("supplier.*,concat(_lok_regencies.name,' - ',_lok_provinces.name) as kota")
            ->orderByDesc('id_supplier')
            ->leftJoin('_lok_regencies', '_lok_regencies.id', '=', 'supplier.id_kota')
            ->leftJoin('_lok_provinces', '_lok_provinces.id', '=', '_lok_regencies.province_id')
            ->whereRaw("
            deleted=0 and
            (
                nama_suplier like ?
                or email like ?
                or phone like ?
                or concat(_lok_regencies.name,' - ',_lok_provinces.name) like ?
            )
            ", ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"])
            ->paginate(5);

        $content_data = array();
        $content_data['supplier'] = $supplier;
        $content = view("admin_supplier.supplier", $content_data);
        $breadcrumb = view('admin_supplier.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Supplier";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit($id = '')
    {
        $form = array();

        $form = new stdClass();
        $form->id = '';
        $form->nama_suplier = '';
        $form->email = '';
        $form->phone = '';
        $form->id_kota = '';
        $form->alamat = '';

        $db_kota = DB::table('_lok_regencies')
            ->selectRaw("_lok_regencies.id,concat(_lok_regencies.name,' - ',_lok_provinces.name) as kota")
            ->leftJoin('_lok_provinces', '_lok_provinces.id', '=', '_lok_regencies.province_id')
            ->get();

        $opt_kota = array();
        $opt_kota[''] = '-- Pilih Kota --';
        foreach ($db_kota as $row) {
            $opt_kota[$row->id] = $row->kota;
        }

        $form->opt_kota = $opt_kota;

        $db = DB::table('supplier')->where('id_supplier', $id)
            ->first();

        if ($db) {
            $form->id = $id;
            $form->nama_suplier = $db->nama_suplier;
            $form->email = $db->email;
            $form->phone = $db->phone;
            $form->id_kota = $db->id_kota;
            $form->alamat = $db->alamat;
        }

        $content_data = array();
        $content_data['form'] = $form;
        $content = view("admin_supplier.supplier_edit", $content_data);
        $breadcrumb = view('admin_supplier.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Supplier";
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
            $validation_rule['nama_suplier'] = 'required';
            $validation_rule['email'] = 'required';
            $validation_rule['phone'] = 'required';
            $validation_rule['id_kota'] = 'required';
        } else {
            $validation_rule['nama_suplier'] = 'required';
            $validation_rule['email'] = 'required';
            $validation_rule['phone'] = 'required';
            $validation_rule['id_kota'] = 'required';
        }


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

        if ($success) {
            $id = trim($request->input('id'));
            if (empty(trim($id))) {
                $insert['nama_suplier'] = $request->input('nama_suplier');
                $insert['email'] = $request->input('email');
                $insert['phone'] = $request->input('phone');
                $insert['id_kota'] = $request->input('id_kota');
                $insert['alamat'] = $request->input('alamat');

                $db = DB::table('supplier')
                    ->insert($insert);
            } else {
                $insert['nama_suplier'] = $request->input('nama_suplier');
                $insert['email'] = $request->input('email');
                $insert['phone'] = $request->input('phone');
                $insert['id_kota'] = $request->input('id_kota');
                $insert['alamat'] = $request->input('alamat');

                $affected = DB::table('supplier')
                    ->where('id_supplier', $id)
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

    function delete($id = '')
    {
        $db = DB::table('supplier')
            ->where('id_supplier', $id)
            ->update(['deleted' => 1]);

        $prev = url()->previous();

        return redirect($prev);
    }
}
