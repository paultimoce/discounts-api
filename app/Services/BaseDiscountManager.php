<?php namespace App\Services;

use App\DiscountRule;
use App\Repositories\CustomerRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\ProductRepository;

class BaseDiscountManager
{
    /**
     * @param array $order
     * @param DiscountRule $rule
     * @return int
     */
    protected function getQuantityOfProductsWithRuleCategory(array $orderItems, int $category) {
        $quantity = 0;

        foreach ($orderItems as $item) {
            $product = $this->productRepository->getProductById($item['product-id']);
            if ($product->category_id == $category) {
                $quantity += $item['quantity'];
            }
        }

        return $quantity;
    }

    /**
     * @param array $order
     * @return float $min - minimum unit price in order
     */
    protected function getCheapestProductPrice(array $order){
        $min = (float)$order['items'][0]['total'];
        foreach ($order['items'] as $item) {
            if ($min > (float)$item['total']) {
                $min = (float)$item['total'];
            }
        }
        return $min;
    }

    /**
     * @param float $value
     * @param float $threshold
     * @param string $operator
     * @return bool true if rule applies, false otherwise
     */
    protected function validateIfRuleApplies(float $value, float $threshold, string $operator){

        if ($operator == 'more' && $value <= $threshold) {
            return false;
        }
        if ($operator == 'less' && $value >= $threshold) {
            return false;
        }
        if ($operator == 'equal' && $value != $threshold) {
            return false;
        }
        if ($operator == 'at_least' && $value < $threshold) {
            return false;
        }
        if ($operator == 'at_most' && $value > $threshold) {
            return false;
        }

        return true;
    }
}