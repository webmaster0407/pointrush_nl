<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'name' => 'Arjan',
            'email' =>'arjan@busgeropvollenbroek.nl',
            'password' => bcrypt('MzsVUSVOU7Tm6fEgbeSK'),
            'roles'=>'0'
        ]);
    }
}
