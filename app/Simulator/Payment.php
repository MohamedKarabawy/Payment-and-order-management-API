<?php

namespace App\Simulator;

use App\DTO\Simulator\CreatePaymentDTO;
use App\DTO\Simulator\TransactionCreateDTO;
use App\Http\Resources\Simulator\PaymentResource;
use App\Jobs\TransactionJob;
use App\Models\GatewayTransaction;
use App\Pipelines\Simulator\IdempotencyStage;
use App\Pipelines\Simulator\ResolveMerchantStage;
use App\Pipelines\Simulator\VerifyApikeyStage;
use App\Pipelines\Simulator\VerifyProvidedSecretStage;
use DB;
use Illuminate\Pipeline\Pipeline;

class Payment
{
    public function simulatePayment(array $payload, string $gateway): array
    {
        $dto = CreatePaymentDTO::fromRequest($payload, $gateway);

        $data = app(Pipeline::class)
            ->send($dto->toPipeline())
            ->through([
                ResolveMerchantStage::class,
                VerifyApikeyStage::class,
                VerifyProvidedSecretStage::class,
                IdempotencyStage::class
            ])
            ->thenReturn();

        if (isset($data['existing_transaction'])) {
            return (new PaymentResource($data['existing_transaction']))->toArray(request());
        }

        $transaction = DB::transaction(function () use ($data) {
            $existing = null;

            if (!empty($data['idempotency_key'])) {
                $existing = GatewayTransaction::where('merchant_id', $data['merchant']->merchant_id)
                    ->where('idempotency_key', $data['idempotency_key'])
                    ->lockForUpdate()
                    ->first();
            }

            if ($existing) {
                return $existing;
            }

            $transactionDTO = TransactionCreateDTO::fromRequest($data);

            $txn = GatewayTransaction::create($transactionDTO->toArray());

            return $txn;
        });

        TransactionJob::dispatch($transaction->id);

        return (new PaymentResource($transaction))->toArray(request());
    }
}