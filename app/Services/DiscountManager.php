<?php namespace App\Services;

use App\DiscountRule;
use App\Repositories\CustomerRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\ProductRepository;

class DiscountManager
{
    /**
     * @var DiscountRepository $discountRepository
     */
    protected $discountRepository;

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
     * @param DiscountRepository $discountRepository
     * @param CustomerRepository $customerRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(DiscountRepository $discountRepository, CustomerRepository $customerRepository, ProductRepository $productRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository  = $productRepository;
    }

    /**
     * @param array $order
     * @return array $discounts
     */
    public function get(array $order) {

        $discountRules = $this->discountRepository->getDiscountRules();

        $discounts = [];

        foreach ($discountRules as $discountRule) {
            switch ($discountRule->type->handle) {
                case "percent_discount_when_total_amount_over_threshold":
                    $discount = $this->percentDiscountWhenTotalAmountOverThreshold($order, $discountRule);

                    if ($discount) {
                        $discounts[] = $discount;
                    }
                    break;
                case "free_product_when_quantity_of_certain_category_over_threshold":
                    $discount = $this->freeProductWhenQuantityOfCertainCategoryOverThreshold($order, $discountRule);
                    if ($discount) {
                        $discounts[] = $discount;
                    }
                    break;
                case "percentage_discount_when_quantity_of_certain_category_over_threshold":
                    $discount = $this->percentageDiscountWhenQuantityOfCertainCategoryOverThreshold($order, $discountRule);
                    if ($discount) {
                        $discounts[] = $discount;
                    }
                    break;
            }
        }

        return $discounts;
    }

    /**
     * Computes an array with discount information if the given rule applies on the given order or returns false if
     * conditions are not met
     *
     * @param array $order
     * @param DiscountRule $rule
     * @return array|bool  - the discount as an array if rule applies on order, boolean false otherwise
     */
    public function percentDiscountWhenTotalAmountOverThreshold(array $order, DiscountRule $rule) {

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

    /**
     * @param array $order
     * @param DiscountRule $rule
     * @return array|bool - the discount as an array if rule applies on order, boolean false otherwise
     */
    public function freeProductWhenQuantityOfCertainCategoryOverThreshold(array $order, DiscountRule $rule) {
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

    /**
     * @param array $order
     * @param DiscountRule $rule
     * @return array|bool - the discount as an array if rule applies on order, boolean false otherwise
     */
    public function percentageDiscountWhenQuantityOfCertainCategoryOverThreshold(array $order, DiscountRule $rule) {

        $response = [
            'discount_type' => $rule->type->handle,
            'discount_type_description' => $rule->type->description,
        ];

        //If the rule does not apply on the current order, return false
        $quantityOfCategory = $this->getQuantityOfProductsWithRuleCategory($order['items'], $rule->category);
        if ($this->validateIfRuleApplies($quantityOfCategory, $rule->quantity, $rule->operator) === false) {
            return false;
        }

        if ($rule->applies == 'whole_order') {
            $response['discount_amount'] = $order['total'] * $rule->percentage / 100;
        }

        if ($rule->applies == 'cheapest_product') {
            $response['cheapest_product_price'] = $this->getCheapestProductPrice($order);
            $response['discount_amount'] = $response['cheapest_product_price'] * $rule->percentage / 100;
        }

        $response['discount_rule'] = $rule->toArray();

        return $response;
    }

    /**
     * @param array $order
     * @param DiscountRule $rule
     * @return int
     */
    private function getQuantityOfProductsWithRuleCategory(array $orderItems, int $category) {
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
    private function getCheapestProductPrice(array $order){
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
    private function validateIfRuleApplies(float $value, float $threshold, string $operator){

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