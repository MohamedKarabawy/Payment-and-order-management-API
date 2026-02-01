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
        Schema::create('pg_payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gateway_name');
            $table->string('merchant_id');
            $table->string('api_key');
            $table->string('secret_key');
            $table->enum('environment', ['sandbox', 'production']);
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
            $table->unique(['gateway_name', 'merchant_id', 'environment']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_payment_gateways');
    }
};
