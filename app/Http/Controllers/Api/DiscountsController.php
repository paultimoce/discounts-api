<?php namespace App\Http\Controllers\Api;

use App\Services\BaseDiscountManager;
use Illuminate\Http\Request;
use App\Http\Requests\GetDiscountsRequest;
use App\DiscountRule;
use App\Http\Controllers\Controller;
use App\Repositories\DiscountRepository;


class DiscountsController extends Controller {

    /**
     * @var DiscountRepository $discountRepository
     */
    protected $discountRepository;

    /**
     * DiscountManager constructor.
     * @param DiscountRepository $discountRepository
     */
    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param GetDiscountsRequest $request
     * @param BaseDiscountManager $discountManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(GetDiscountsRequest $request) {

        $order = $request->all();

        $discountRules = $this->discountRepository->getDiscountRules();

        $discounts = [];

        foreach ($discountRules as $rule) {

            /**
             * Using Laravels service provider mechanism to properly instanciate the
             * proper discount manager based on the discount_types.handle field
             *
             * Please refer to the App\Providers\DiscountRuleServiceProvider for more detail
             *
             */
            $discountManager = app()->make($rule->type->handle);

            $computedDiscounts = $discountManager->compute($order, $rule);

            if ($computedDiscounts !== false) {
                $discounts[] = $computedDiscounts;
            }
        }

        return response()->json([
            'data' => [
                'order' => $request->all(),
                'discounts' => $discounts
            ]
        ]);
    }
}