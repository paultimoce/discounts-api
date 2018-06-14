<?php namespace App\Repositories;

use App\Product;

class ProductRepository
{
    /**
     * @param string $id
     * @return Product|null - Product instance or null if id not found
     */
    public function getProductById(string $id)
    {
        return Product::whereId($id)->first();
    }
}