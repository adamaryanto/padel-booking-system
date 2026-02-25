<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Midtrans callback notification.
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'];
        
        $this->paymentService->syncTransactionStatus($orderId);

        return response()->json(['status' => 'success']);
    }
}
