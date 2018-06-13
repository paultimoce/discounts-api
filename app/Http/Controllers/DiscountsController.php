<?php namespace App\Http\Controllers;

use App\Services\DiscountManager;
use Illuminate\Http\Request;
use App\Http\Requests\GetDiscountsRequest;
use App\DiscountRule;

class DiscountsController extends Controller {

    public function get(GetDiscountsRequest $request, DiscountManager $discountManager) {
        $discounts = $discountManager->get($request->all());
        dd($discounts);
    }
}