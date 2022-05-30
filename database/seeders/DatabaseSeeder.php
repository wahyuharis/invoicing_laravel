<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

     

        for ($i = 0; $i < 100; $i++) {
            $insert1 = array();
            $insert1['nama_category'] = Str::random(10);
            $master_category = DB::table('master_category')->insert($insert1);
        }
    }
}
