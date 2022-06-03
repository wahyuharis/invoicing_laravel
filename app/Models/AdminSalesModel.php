<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminSalesModel extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'id_sales';

    function get_sisa_tagihan($id_sales){
        $sql="SELECT  
        (sales.total - sum(cashflow.total)) as sisa_tagihan
        FROM sales
        left join cashflow on cashflow.id_tabel=sales.id_sales
        where cashflow.tabel='sales'
        and sales.id_sales= ?
        GROUP By sales.id_sales
        limit 1";

        $db=DB::select($sql,[$id_sales]);

        return $db;
    }
}
