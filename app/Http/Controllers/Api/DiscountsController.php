<?php namespace App\Http\Controllers\Api;

use App\Services\DiscountManager;
use Illuminate\Http\Request;
use App\Http\Requests\GetDiscountsRequest;
use App\DiscountRule;
use App\Http\Controllers\Controller;


class DiscountsController extends Controller {

    /**
     * @param GetDiscountsRequest $request
     * @param DiscountManager $discountManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(GetDiscountsRequest $request, DiscountManager $discountManager) {
        $discounts = $discountManager->get($request->all());
        return response()->json([
            'data' => [
                'order' => $request->all(),
                'discounts' => $discounts
            ]
        ]);
    }
}