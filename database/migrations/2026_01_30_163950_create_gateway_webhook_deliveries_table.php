<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sm_gateway_webhook_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('merchant_id')->index();
            $table->string('transaction_id')->index();
            $table->string('event_type');
            $table->json('payload');
            $table->string('target_url');
            $table->string('signature');
            $table->enum('status', ['pending', 'delivered', 'failed'])->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('last_attempted_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_gateway_webhook_deliveries');
    }
};
