<?php

namespace App\Http\Controllers\Simulator;

use App\DTO\Simulator\GenerateGatewayDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Simulator\GenerateGatewayRequest;
use App\Models\GatewayMerchant;
use App\Pipelines\Simulator\PreventDuplicateGatewayStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;

class GatewaySimulatorController extends Controller
{
    public function generate(GenerateGatewayRequest $request): JsonResponse
    {
        $gatewayData = GenerateGatewayDTO::fromRequest($request->validated());

        $data = app(Pipeline::class)
            ->send($gatewayData->toPipeline())
            ->through([
                PreventDuplicateGatewayStage::class
            ])
            ->thenReturn();

        $merchant = GatewayMerchant::generate(
            $data['gateway'],
            $data['environment']
        );

        return response()->json($merchant);
    }

    public function list(): JsonResponse
    {
        return response()->json(GatewayMerchant::all());
    }
}
