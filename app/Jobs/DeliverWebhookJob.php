<?php

namespace App\Jobs;

use App\Models\GatewayWebhookDelivery;
use DB;
use Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeliverWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $deliveryId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () 
        {
            $delivery = GatewayWebhookDelivery::lockForUpdate()
                ->find($this->deliveryId);

            if (!$delivery || $delivery->status === 'delivered') 
            {
                return;
            }

            $response = Http::timeout(5)
                ->withHeaders(['X-Signature' => $delivery->signature])
                ->post($delivery->target_url, $delivery->payload);

            $delivery->attempts++;
            $delivery->last_attempted_at = now();

            if ($response->successful()) 
            {
                $delivery->status = 'delivered';
                $delivery->delivered_at = now();
            } 
            else 
            {
                $delivery->status = 'failed';

                if ($delivery->attempts < 5) 
                {
                    self::dispatch($delivery->id)
                        ->delay(now()->addSeconds(2 ** $delivery->attempts));
                }
            }

            $delivery->save();
        });
    }
}
