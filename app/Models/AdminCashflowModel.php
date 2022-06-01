<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AdminCashflowModel extends Model
{
    use HasFactory;
    protected $table = 'purchase';
    protected $primaryKey = 'id_purchase';

    function get_list()
    {
        $sql = "SELECT 

        cashflow.id_cashflow,
        cashflow.keperluan,
        cashflow.tabel,
        cashflow.id_tabel,
        cashflow.total,
        (case WHEN cashflow.tabel='purchase'
            then (
                SELECT purchase.kode_purchase 
                FROM `purchase` 
                WHERE purchase.id_purchase=cashflow.id_tabel
                limit 1
            )
         else 'kondisi lain'
         END
        ) as kode_trans
        
        
        FROM `cashflow` WHERE 1;
        ";

        DB::select($sql, []);
    }
}
