<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AdminPurchaseModel extends Model
{
    use HasFactory;
    protected $table = 'purchase';
    protected $primaryKey = 'id_purchase';

    function get_sisa_tagihan($id)
    {
        $sql = "SELECT  
        (purchase.total + sum(cashflow.total)) as sisa_tagihan
        FROM purchase
        left join cashflow on cashflow.id_tabel=purchase.id_purchase
        where cashflow.tabel='purchase'
        and purchase.id_purchase= ?
        GROUP By purchase.id_purchase
        limit 1";

        $db = DB::select($sql, [$id]);

        return $db;
    }
}
