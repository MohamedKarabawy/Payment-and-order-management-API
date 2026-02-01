<?php

namespace App\Http\Controllers;

use App\DTO\Payments\PaymentDTO;
use App\DTO\Payments\StorePaymentDTO;
use App\Payments\PaymentMethodResolver;
use App\Http\Requests\Payments\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Pipelines\PreventUnconfirmedOrderPayment;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pipeline\Pipeline;

class PaymentsController extends Controller
{
    public function __construct(private PaymentMethodResolver $resolver)
    {}

    public function index(): AnonymousResourceCollection
    {
        $payment = Payment::query()->paginate(10);

        return PaymentResource::collection($payment);
    }

    public function pay(PaymentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $order = Order::findOrFail($data['orderId']);

        app(Pipeline::class)
            ->send($order->id)
            ->through([
                PreventUnconfirmedOrderPayment::class
            ])
            ->thenReturn();

        $paymentMethod = PaymentGateway::where('gateway_name', $data['paymentMethod'])->first();

        $payment = $this->resolver->resolve($data['paymentMethod']);

        $paymentDTO = PaymentDTO::fromRequest($order, $paymentMethod);

        $payment = $payment->pay($paymentDTO->toArray());

        $storePaymentDTO = StorePaymentDTO::fromRequest($order, $payment, $paymentMethod);

        $details = DB::transaction(function () use ($order, $storePaymentDTO) {

            $payment = Payment::create($storePaymentDTO->toArray());

            $order->update([
                "payment_status" => $payment->status,
                "payment_method" => $payment->gateway_name,
                "transaction_id" => $payment->transaction_id
            ]);

            return $payment;
        });

        return response()->json(["message" => "Payment Done Succussfully", "details" => new PaymentResource($details)]);

    }
}
