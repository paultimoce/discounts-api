<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 15/06/2018
 * Time: 13:26
 */

namespace App\Services;


use App\Contracts\DiscountRuleCalculatorContract;
use App\DiscountRule;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;


class FreeProductWhenQuantityOfCertainCategoryOverThreshold extends BaseDiscountManager implements DiscountRuleCalculatorContract
{

    /**
     * @var ProductRepository $productRepository
     */
    protected $productRepository;

    /**
     * @var CustomerRepository $customerRepository
     */
    protected $customerRepository;

    /**
     * DiscountManager constructor.
     * @param CustomerRepository $customerRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(CustomerRepository $customerRepository, ProductRepository $productRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->productRepository  = $productRepository;
    }

    /**
     * @param array $order
     * @param DiscountRule $rule
     * @return array|bool - the discount as an array if rule applies on order, boolean false otherwise
     */
    public function compute(array $order, DiscountRule $rule)
    {
        $response = false;

        $numExtraItems = [];
        foreach ($order['items'] as $item) {
            $product = $this->productRepository->getProductById($item['product-id']);
            if ($product->category_id == $rule->category && $item['quantity'] >= $rule->threshold) {
                $numExtraItems[$item['product-id']] = (int)floor($item['quantity'] / (float)$rule->threshold);
            }
        }

        if (!empty($numExtraItems)) {
            $response = [
                'discount_type' => $rule->type->handle,
                'discount_type_description' => $rule->type->description,
                'discount' => 'extra_items',
                'extra_items' => $numExtraItems,
                'discount_rule' => $rule->toArray(),

            ];
        }

        return $response;
    }

}