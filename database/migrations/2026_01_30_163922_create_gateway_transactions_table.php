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
        Schema::create('sm_gateway_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('merchant_id')->index();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('status', ['pending', 'succeeded', 'failed']);
            $table->string('idempotency_key')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_gateway_transactions');
    }
};
