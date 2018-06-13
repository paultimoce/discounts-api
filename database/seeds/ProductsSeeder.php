<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 11/06/2018
 * Time: 21:52
 */

use Illuminate\Database\Seeder;
use App\Product;

class ProductsSeeder extends Seeder
{
    public function run(){

        Product::truncate();
        Product::create(['id' => 'A101', 'description' => 'Screwdriver', 'category_id' => 1, 'price' => 9.75]);
        Product::create(['id' => 'A102', 'description' => 'Electric Screwdriver', 'category_id' => 1, 'price' => 49.5]);
        Product::create(['id' => 'B101', 'description' => 'Basic on-off switch', 'category_id' => 2, 'price' => 4.99]);
        Product::create(['id' => 'B102', 'description' => 'Press button', 'category_id' => 2, 'price' => 4.99]);
        Product::create(['id' => 'B103', 'description' => 'Switch with motion detector', 'category_id' => 2, 'price' => 4.99]);
    }

}