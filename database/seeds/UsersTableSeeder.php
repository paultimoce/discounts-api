<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 11/06/2018
 * Time: 21:52
 */

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    public function run(){

        User::truncate();
        User::create(['email' => 'tester@teamleader.com', 'name' => 'John Doe', 'password' => bcrypt('test123')]);
    }

}