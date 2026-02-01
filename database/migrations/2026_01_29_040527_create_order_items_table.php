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
        Schema::create('pg_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('pg_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('pg_items')->onUpdate('cascade');
            $table->unique(['order_id', 'item_id']);
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->decimal( 'product_price', 10, 2);
            $table->integer('product_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_order_items');
    }
};
