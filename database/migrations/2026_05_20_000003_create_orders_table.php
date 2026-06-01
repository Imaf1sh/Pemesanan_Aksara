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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary(); // Using string ID like 'ord_xxx'
            $table->string('customer_name')->default('Guest');
            $table->integer('total');
            $table->string('payment_method')->default('Cash');
            $table->string('order_type')->default('Dine In');
            $table->string('status')->default('pending'); // pending, completed
            $table->dateTime('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
