<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Petya',
            'surname' => 'Maiko',
            'mail' => 'petyamaiko@gmail.com',
            'password' => bcrypt('qwe123'),
            'image' => '/ava/1.jpg',
            'api_token' => Str::random(60),
        ]);
    }
}
