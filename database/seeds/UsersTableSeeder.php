<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salt = 'secret';
       	DB::table('users')->insert([
            'name' => 'bank',
            'email' => 'admin'.'@gmail.com',
            'salt' => $salt,
            'password' => bcrypt($salt.'secret'),
            'user_type' => 'admin',
            'role_id' => '1',
            'status' => '1',
        ]);
    }
}
