<?php

namespace Database\Seeders;

use App\Models\Sake;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<11; $i++){
            DB::table('posts')->insert([
                'user_id' => User::find($i)->id,
                'sake_id' => Sake::inRandomOrder()->first()->id,
                'content' => 'これはダミー文章です。',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
