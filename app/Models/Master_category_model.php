<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master_category_model extends Model
{
    use HasFactory;

    protected $table = 'master_category';
    protected $primaryKey = 'id_category';
}
