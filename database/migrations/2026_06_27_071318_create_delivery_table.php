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
    Schema::create('delivery', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id')->unique();
        $table->string('rider_name', 100)->nullable();
        $table->dateTime('slot')->nullable();
        $table->enum('status', ['pending', 'assigned', 'delivered'])->default('pending');
        $table->foreign('order_id')->references('id')->on('orders');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery');
    }
};
