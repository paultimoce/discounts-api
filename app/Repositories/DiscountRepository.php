<?php namespace App\Repositories;

use App\DiscountRule;

class DiscountRepository
{
    /**
     * @return DiscountRule[] - Collection of discount rules with eager loaded discount type
     */
    public function getDiscountRules()
    {
        return DiscountRule::with('type')->get();
    }
}