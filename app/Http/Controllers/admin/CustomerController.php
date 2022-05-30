<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class CustomerController extends Controller
{
    //
    function index(Request $request)
    {
        $searchTerm = $request->input('search');

        $customer = DB::table('customer')
            ->where('deleted', 0)
            ->orderByDesc('id_customer')
            ->where('nama_customer', 'LIKE', "%{$searchTerm}%")
            ->paginate(5);

        $content_data = array();
        $content_data['customer'] = $customer;
        $content = view("admin_customer.customer", $content_data);
        $breadcrumb = view('admin_customer.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Customer";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit($id = '')
    {
        $form = array();

        $form = new stdClass();
        $form->id = '';
        $form->nama_customer = '';
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

        $db = DB::table('customer')->where('id_customer', $id)
            ->first();

        if ($db) {
            $form->id = $id;
            $form->nama_customer = $db->nama_customer;
            $form->email = $db->email;
            $form->phone = $db->phone;
            $form->id_kota = $db->id_kota;
            $form->alamat = $db->alamat;
        }

        $content_data = array();
        $content_data['form'] = $form;
        $content = view("admin_customer.customer_edit", $content_data);
        $breadcrumb = view('admin_supplier.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Customer";
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
            $validation_rule['nama_customer'] = 'required';
            $validation_rule['email'] = 'required';
            $validation_rule['phone'] = 'required';
            $validation_rule['id_kota'] = 'required';
        } else {
            $validation_rule['nama_customer'] = 'required';
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
                $insert['nama_customer'] = $request->input('nama_customer');
                $insert['email'] = $request->input('email');
                $insert['phone'] = $request->input('phone');
                $insert['id_kota'] = $request->input('id_kota');
                $insert['alamat'] = $request->input('alamat');

                $db = DB::table('customer')
                    ->insert($insert);
            } else {
                $insert['nama_customer'] = $request->input('nama_customer');
                $insert['email'] = $request->input('email');
                $insert['phone'] = $request->input('phone');
                $insert['id_kota'] = $request->input('id_kota');
                $insert['alamat'] = $request->input('alamat');

                $affected = DB::table('customer')
                    ->where('id_customer', $id)
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
        $db = DB::table('customer')->where('id_customer', $id)
            ->update(['deleted' => 1]);

        $prev = url()->previous();

        return redirect($prev);
    }

}
