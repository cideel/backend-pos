<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->string('order_status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('order_date')->useCurrent();
            $table->string('table_number')->nullable();
            $table->timestamps();
        });
    }
}
