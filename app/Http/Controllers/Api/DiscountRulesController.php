<?php

namespace App\Http\Controllers\Api;

use App\DiscountType;
use App\Http\Requests\UpdateDiscountRuleRequest;
use Illuminate\Http\Request;
use App\DiscountRule;
use App\Http\Requests\CreateDiscountRuleRequest;
use App\Http\Controllers\Controller;

class DiscountRulesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = DiscountRule::with('type')->get();
        return response()->json(['data' => $rules]);
    }

    public function store(CreateDiscountRuleRequest $request)
    {
        $discountType = DiscountType::whereHandle($request->type)->first();

        $rule = $request->all();
        $rule['discount_type_id'] =  $discountType->id;

        return response()->json(['data' => DiscountRule::create($rule)], 201);
    }

    /**
     * @param string $version
     * @param int $id
     * @param UpdateDiscountRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $version, int $id, UpdateDiscountRuleRequest $request)
    {
        $data = $request->all();
        $discountType = DiscountType::whereHandle($request->type)->first();
        $data['discount_type_id'] =  $discountType->id;

        $request->merge(['id' => $id]);
        $this->validate($request, [
            'id' => 'exists:discount_rules,id'
        ], [
            'id.exists' => 'Invalid discount rule id'
        ]);

        $rule = DiscountRule::find($id);
        $rule->fill($data);
        $rule->save();

        return response()->json(['data' => $rule]);
    }

    /**
     * @param string $version
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $version, int $id)
    {
        $rule = DiscountRule::find($id);
        if (!empty($rule)) {
            $rule->delete();
        }
        return response()->json(['remaining_rules' => DiscountRule::with('type')->get()]);
    }
}
