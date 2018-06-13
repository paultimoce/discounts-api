<?php
/**
 * Created by PhpStorm.
 * User: paultimoce
 * Date: 11/06/2018
 * Time: 21:52
 */

use Illuminate\Database\Seeder;
use App\DiscountRule;
use App\DiscountType;

class DiscountSeeder extends Seeder
{
    public function run(){

        DiscountRule::truncate();
        DiscountType::truncate();

        //Create initial discount types
        $discountType1 = DiscountType::create([
            'handle' => 'percent_discount_when_total_amount_over_threshold',
            'description' => 'A customer who has already bought for more (or less) than a set threshold value, gets a discount of a certain percentage on the whole order'
        ]);

        $discountType2 = DiscountType::create([
            'handle' => 'free_product_when_quantity_of_certain_category_over_threshold',
            'description' => 'For every product of a certain category, when you buy at least a certain quantity, you get another product of the same type for free.'
        ]);

        $discountType3 = DiscountType::create([
            'handle' => 'percentage_discount_when_quantity_of_certain_category_over_threshold',
            'description' => 'If you buy a certain quantity of products of a certain category, you get a percentage discount on the cheapest product.'
        ]);

        //Create the actual rules
        DiscountRule::create(['percentage' => 10, 'threshold' => 1000, 'operator' => 'more', 'applies' => 'whole_order', 'discount_type_id' => $discountType1->id]);
        DiscountRule::create(['category' => 2, 'threshold' => 5, 'discount_type_id' => $discountType2->id]);
        DiscountRule::create(['percentage' => 20, 'category' => 1, 'quantity' => 2, 'operator' => 'at_least', 'applies' => 'cheapest_product', 'discount_type_id' => $discountType3->id]);

    }

}