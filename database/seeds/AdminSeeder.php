<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            "name"=>"abdo" ,
            "email"=>"01012617633abdoshanan@gmail.com",
            "password"=>bcrypt("asd3450326")
        ]);
    }
}
