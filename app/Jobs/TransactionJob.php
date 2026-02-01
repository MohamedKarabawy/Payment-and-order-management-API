<?php

namespace App\Jobs;

use App\Http\Resources\Simulator\GatewayWebhookResource;
use App\Models\GatewayTransaction;
use App\Models\GatewayWebhookDelivery;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $transactionId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
          DB::transaction(function () 
          {
            $tx = GatewayTransaction::lockForUpdate()->find($this->transactionId);

            if (!$tx || $tx->status !== 'pending')
            {
                return;
            }

            $cents = (int) round($tx->amount * 100);

            $succeed = $cents % 2 === 0;

            $tx->status = $succeed ? 'succeeded' : 'failed';

            $tx->save();

            $merchant = $tx->merchant()->first();

            if (!$merchant) 
            {
                return;
            }

            $payload = GatewayWebhookResource::make($tx)->toArray(null);

            $signature = hash_hmac('sha256', json_encode($payload), $merchant->secret_key);

            $target = $tx->callback_url ?? ($merchant->callback_url ?? ($merchant->default_callback ?? ''));

            $delivery = GatewayWebhookDelivery::create([
                'merchant_id' => $merchant->merchant_id,
                'transaction_id' => $tx->transaction_id,
                'event_type' => $tx->status === 'succeeded' ? 'payment.succeeded' : 'payment.failed',
                'payload' => $payload,
                'target_url' => $target,
                'signature' => $signature,
                'status' => 'pending'
            ]);

            DeliverWebhookJob::dispatch($delivery->id);
        });
    }
}
