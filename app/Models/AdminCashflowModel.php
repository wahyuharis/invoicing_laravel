<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AdminCashflowModel extends Model
{
    use HasFactory;
    protected $table = 'cashflow';
    protected $primaryKey = 'id_cashflow';

    function get_list()
    {
        $selectRaw="cashflow.id_cashflow,
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
        ) as kode_trans";

        $db=DB::table('cashflow')
        ->selectRaw($selectRaw)
        ->paginate(5);

        return $db;

    }
}
