<?php

namespace App\Http\Controllers;

use App\DTO\Orders\OrderDTO;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Pipelines\PreventPaidOrdersDeletion;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pipeline\Pipeline;

class OrdersController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $orders = Order::query()->paginate(10);

        return OrderResource::collection($orders);
    }

    public function show(int $id): OrderResource
    {
        $order = Order::findOrFail($id);

        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $orderData = DB::transaction(function () use ($request) {

            $orderData = OrderDTO::fromRequest($request->validated());

            $order = Order::create($orderData->toArray());

            $order->attachItems($request->input('order_items'));

            $order->load('orderItems');

            return $order;
        });

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($orderData)
        ], 201);
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        $orderData = OrderDTO::fromRequest($request->validated(), $order);

        $order_details = DB::transaction(function () use ($order, $orderData, $request) {

            if(count($orderData->toArray()) > 2)
                 $order->update($orderData->toArray());

            if(!empty($request->input('order_items'))) 
                $order->attachItems($request->input('order_items'));

            return $order;
        });

        $order->load('orderItems');

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => new OrderResource($order_details)
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        app(Pipeline::class)
            ->send($id)
            ->through([
                PreventPaidOrdersDeletion::class
            ])
            ->thenReturn();

        $order = Order::findOrFail($id);

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}
