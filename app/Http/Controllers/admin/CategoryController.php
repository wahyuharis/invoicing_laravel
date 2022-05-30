<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Validator;

class CategoryController extends Controller
{
    //
    function index(Request $request)
    {

        $searchTerm = $request->input('search');


        $category = DB::table('master_category')
            ->where('deleted', 0)
            ->orderByDesc('id_category')
            ->where('nama_category', 'LIKE', "%{$searchTerm}%")
            ->paginate(5);


        $content_data = array();
        $content_data['category'] = $category;
        $content = view("admin_category.category", $content_data);
        $breadcrumb = view('admin_category.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Category Item";
        $layout_data['content'] = $content;
        $layout_data['breadcrumb'] = $breadcrumb;

        return view('admin.layout', $layout_data);
    }

    function edit($id = '')
    {
        $form = array();

        $form = new stdClass();
        $form->id = '';
        $form->nama_category = '';

        $db = DB::table('master_category')->where('id_category', $id)
            ->first();

        if ($db) {
            $form->id = $id;
            $form->nama_category = $db->nama_category;
        }

        $content_data = array();
        $content_data['form'] = $form;
        $content = view("admin_category.category_edit", $content_data);
        $breadcrumb = view('admin_category.breadcrumb');

        $layout_data = array();
        $layout_data['page_title'] = "Category Item";
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
            $validation_rule['nama_category'] = 'required';
        } else {
            $validation_rule['nama_category'] = 'required';
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
                $insert['nama_category'] = trim($request->input('nama_category'));

                $db = DB::table('master_category')
                    ->insert($insert);

                $insert_id = DB::getPdo()->lastInsertId();
            } else {

                $insert['nama_category'] = trim($request->input('nama_category'));

                $affected = DB::table('master_category')
                    ->where('id_category', $id)
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

        // $data = $request->session()->all();

        $db = DB::table('master_category')
            ->where('id_category', $id)
            ->update(['deleted'=> 1 ]);

        $prev = url()->previous();
        
        return redirect($prev);
    }
}
