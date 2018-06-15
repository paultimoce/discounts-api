<?php

namespace Tests\Unit;

use App\DiscountRule;
use App\DiscountType;
use App\Repositories\DiscountRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscountTest extends TestCase
{
    protected $discountRepository;

    protected $order = [
        'customer-id' => 2,
        'items' => [
            [
                'product-id' => "B102",
                'unit-price' => 4.90,
                'quantity' => 10,
                'total' => 49.90
            ]
        ],
        'total' => 49.90
    ];

    public function testPercentDiscountWhenTotalAmountOverThreshold()
    {
        $type = DiscountType::whereHandle('percent_discount_when_total_amount_over_threshold')->first();
        $rule = new DiscountRule(['percentage' => 10, 'threshold' => 1000, 'operator' => 'more', 'applies' => 'whole_order', 'discount_type_id' => $type->id]);

        $discountManager = app()->make('percent_discount_when_total_amount_over_threshold');
        $discount = $discountManager->compute($this->order, $rule);

        //The expected response from compute method is now an array containing a discount amount
        $this->assertNotFalse( $discount);

        //now change the rule so that the discount will not apply anymore and assert again
        $rule->threshold = 10000000;

        $discount = $discountManager->compute($this->order, $rule);

        //The expected response from the compute method is boolean false in this case
        $this->assertFalse($discount);
    }

    public function testFreeProductWhenQuantityOfCertainCategoryOverThreshold()
    {
        $type = DiscountType::whereHandle('free_product_when_quantity_of_certain_category_over_threshold')->first();
        $rule = new DiscountRule(['category' => 2, 'threshold' => 5, 'discount_type_id' => $type->id]);

        $discountManager = app()->make('free_product_when_quantity_of_certain_category_over_threshold');
        $discount = $discountManager->compute($this->order, $rule);

        //The expected response from compute method is now an array containing an extra_items key
        $this->assertNotFalse( $discount);

        /**
         * If we change either the category or the threshold of the discount rule
         * we expect $discountManager->compute to return boolean false since the rule
         * doesn't apply to this order anymore
         */
        $rule->threshold = 100; //in the order above we only buy a quantity of 10
        $discount = $discountManager->compute($this->order, $rule);

        $this->assertFalse($discount);

        $rule->category = 1;
        $rule->threshold = 10;

        $discount = $discountManager->compute($this->order, $rule);
        $this->assertFalse($discount);
    }

    public function testPercentageDiscountWhenQuantityOfCertainCategoryOverThreshold()
    {
        $type = DiscountType::whereHandle('percentage_discount_when_quantity_of_certain_category_over_threshold')->first();
        $rule = new DiscountRule(['percentage' => 20, 'category' => 2, 'quantity' => 2, 'operator' => 'at_least', 'applies' => 'cheapest_product', 'discount_type_id' => $type->id]);

        $discountManager = app()->make('percentage_discount_when_quantity_of_certain_category_over_threshold');
        $discount = $discountManager->compute($this->order, $rule);

        //The expected response from compute method is now an array containing an extra_items key
        $this->assertNotFalse( $discount);

        /**
         * make sure rule does not apply since the order is for quantity 10
         * and this is not less than 2 (which is the rule quantity)
         */
        $rule->operator = 'less';
        $discount = $discountManager->compute($this->order, $rule);
        $this->assertFalse($discount);

        $rule->operator = 'at_least'; // switch operator to initial value
        $rule->category = 1; //The order we have has no category 1 products so we expect boolean false here
        $discount = $discountManager->compute($this->order, $rule);
        $this->assertFalse($discount);
    }


}
