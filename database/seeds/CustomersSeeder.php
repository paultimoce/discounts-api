<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 11/06/2018
 * Time: 21:52
 */

use Illuminate\Database\Seeder;
use App\Customer;

class CustomersSeeder extends Seeder
{
    public function run(){

        Customer::truncate();
        Customer::create(['name' => 'Coca Cola', 'since' => '2014-06-28', 'revenue' => 492.12]);
        Customer::create(['name' => 'Teamleader', 'since' => '2015-01-1', 'revenue' => 1505.95]);
        Customer::create(['name' => 'Jeroen De Wit', 'since' => '2016-02-11']);
    }

}