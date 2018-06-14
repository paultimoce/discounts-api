<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\UpdateDiscountRuleRequest;
use App\Http\Requests\CreateDiscountRuleRequest;
use App\Http\Controllers\Api\DiscountRulesController as Base;

class DiscountRulesController extends Base
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json('this is v2');
    }

    public function store(CreateDiscountRuleRequest $request)
    {
        return response()->json('this is v2');

    }

    public function update($id, UpdateDiscountRuleRequest $request)
    {
        return response()->json('this is v2');

    }

    public function destroy($id)
    {
        return response()->json('this is v2');
    }
}
