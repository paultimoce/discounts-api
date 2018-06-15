<?php namespace App\Contracts;

use App\DiscountRule;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;

interface DiscountRuleCalculatorContract {
    public function __construct(CustomerRepository $customerRepository, ProductRepository $productRepository);
    public function compute(array $order, DiscountRule $rule);
}