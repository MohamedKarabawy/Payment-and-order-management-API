<?php

namespace App\Http\Controllers;

use App\DTO\Gateways\GatewayDTO;
use App\DTO\Gateways\GatewayNameDTO;
use App\Gateways\PaymentGatewayResolver;
use App\Http\Requests\Gateways\CreateGatewayRequest;
use App\Http\Requests\Gateways\UpdateGatewayRequest;
use App\Http\Resources\GatewayResource;
use App\Models\PaymentGateway;
use App\Pipelines\PreventDuplicateGateway;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PaymentGatewaysController extends Controller
{
    public function __construct(private PaymentGatewayResolver $resolver)
    {}

    public function index()
    {
        $gateways = PaymentGateway::query()->paginate(10);

        return GatewayResource::collection($gateways);
    }

    public function store(CreateGatewayRequest $request, string $gateway)
    {
        $validatedGateway = Validator::make(
            ['gateway' => $gateway],
            ['gateway' => ['required', Rule::in(['paypal', 'stripe', 'visa'])]]
        );

        $gatewayNameDTO = GatewayNameDTO::fromRequest($validatedGateway->validate());

        $gateway = app(Pipeline::class)
            ->send($gatewayNameDTO->toPipeline())
            ->through([
                PreventDuplicateGateway::class
            ])
            ->thenReturn();

        $gatewayDTO = GatewayDTO::fromRequest($request->validated());

        $paymentGateway = $this->resolver->resolve($gateway);

        $paymentGateway = $paymentGateway->addGateway($gatewayDTO->toArray());

        return response()->json(["message" => 'Payment Gateway Created Successfully.', "data" => new GatewayResource($paymentGateway)], 201);
    }

    public function update(UpdateGatewayRequest $request, string $gateway)
    {
        $validatedGateway = Validator::make(
            ['gateway' => $gateway],
            ['gateway' => ['required', Rule::in(['paypal', 'stripe', 'visa'])]]
        );

        $paymentGateway = $this->resolver->resolve($validatedGateway->validate()['gateway']);

        $gatewayDTO = GatewayDTO::fromRequest($request->validated());

        $paymentGateway = $paymentGateway->updateGateway($gatewayDTO->toArray());

        return response()->json(["message" => 'Payment Gateway Updated Successfully.', "data" => new GatewayResource($paymentGateway)], 201);
    }

    public function destroy(string $gateway)
    {
        $validatedGateway = Validator::make(
            ['gateway' => $gateway],
            ['gateway' => ['required', Rule::in(['paypal', 'stripe', 'visa'])]]
        );

        $paymentGateway = $this->resolver->resolve($validatedGateway->validate()['gateway']);

        $paymentGateway->deleteGateway();

        return response()->json(["message" => 'Payment Gateway Deleted Successfully.'], 201);
    }
}
