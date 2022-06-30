<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<4; $i++){
            DB::table('sakes')->insert([
                'sake_name' => '酒の名前'.$i,
                'kuramoto' => '蔵元'.$i,
                'prefecture' => '愛知県',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
