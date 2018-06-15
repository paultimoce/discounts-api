<?php namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use App\Services\FreeProductWhenQuantityOfCertainCategoryOverThreshold;
use App\Services\PercentageDiscountWhenQuantityOfCertainCategoryOverThreshold;
use App\Services\PercentDiscountWhenTotalAmountOverThreshold;
use Illuminate\Support\ServiceProvider;

class DiscountRuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->singleton('percent_discount_when_total_amount_over_threshold', function(){
            return new PercentDiscountWhenTotalAmountOverThreshold(new CustomerRepository(), new ProductRepository());
        });
        app()->singleton('free_product_when_quantity_of_certain_category_over_threshold', function(){
            return new FreeProductWhenQuantityOfCertainCategoryOverThreshold(new CustomerRepository(), new ProductRepository());
        });
        app()->singleton('percentage_discount_when_quantity_of_certain_category_over_threshold', function(){
            return new PercentageDiscountWhenQuantityOfCertainCategoryOverThreshold(new CustomerRepository(), new ProductRepository());
        });
    }
}
