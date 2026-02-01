<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pg_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->string('order_no')->unique()->nullable();
            $table->enum('currency', ['USD', 'EUR', 'EGP'])->default('EGP');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('customer_name');
            $table->string('billing_email');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_country');
            $table->string('billing_phone');
            $table->string('billing_zip_code');
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_orders');
    }
};
