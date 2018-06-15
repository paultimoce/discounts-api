<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 15/06/2018
 * Time: 13:24
 */

namespace App\Services;


use App\Contracts\DiscountRuleCalculatorContract;
use App\DiscountRule;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;

class PercentDiscountWhenTotalAmountOverThreshold extends BaseDiscountManager implements DiscountRuleCalculatorContract
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
     * Computes an array with discount information if the given rule applies on the given order or returns false if
     * conditions are not met
     *
     * @param array $order
     * @param DiscountRule $rule
     * @return array|bool  - the discount as an array if rule applies on order, boolean false otherwise
     */
    public function compute(array $order, DiscountRule $rule)
    {
        $response = [
            'discount_type' => $rule->type->handle,
            'discount_type_description' => $rule->type->description,
        ];

        $customer = $this->customerRepository->getCustomerById($order['customer-id']);
        if ($this->validateIfRuleApplies($customer->revenue, $rule->threshold, $rule->operator) === false) {
            return false;
        }

        //If we got this far it means the rule actually applies to the order so compute the discount
        if ($rule->applies == 'whole_order') {
            $response['discount_amount'] = $order['total'] * $rule->percentage / 100;
        }

        if ($rule->applies == 'cheapest_product') {
            $response['cheapest_product_price'] = $this->getCheapestProductPrice($order);
            $response['discount_amount'] = $response['cheapest_product_price'] * $rule->percentage / 100;
        }

        $response['customer_revenue'] = $customer->revenue;
        $response['discount_rule'] = $rule->toArray();

        return $response;
    }
}