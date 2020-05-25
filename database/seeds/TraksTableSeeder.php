<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TraksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tracks')->insert([
            'src' => '/music_upload/default/1.mp3',
            'name_track' => '1',
            'playlist' => '[]',
            'user_id' => 1,
        ]);

        DB::table('tracks')->insert([
            'src' => '/music_upload/default/2.mp3',
            'name_track' => '2',
            'playlist' => '[]',
            'user_id' => 1,
        ]);
        DB::table('tracks')->insert([
            'src' => '/music_upload/default/2.mp3',
            'name_track' => '2',
            'playlist' => '[]',
            'user_id' => 2,
        ]);
        DB::table('tracks')->insert([
            'src' => '/music_upload/default/2.mp3',
            'name_track' => '2',
            'playlist' => '[]',
            'user_id' => 3,
        ]);
    }
}
