<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Services\AffiliateService;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Complete this method

        $data = [
            'order_id' => $request->order_id ,
            'subtotal_price' => $request->subtotal_price ,
            'merchant_domain' => $request->merchant_domain ,
            'discount_code' => $request-> discount_code
        ];

        $this->orderService->processOrder($data);

         return response()->json(200);

    }
}
