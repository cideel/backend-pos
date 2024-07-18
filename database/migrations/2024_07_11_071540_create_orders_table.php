<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key otomatis bernama 'id'
            $table->unsignedBigInteger('customer_id'); // Foreign key ke tabel 'customers'
            $table->foreign('customer_id')->references('customer_id')->on('customers'); // Mengacu pada 'customer_id'
            $table->string('order_status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('order_date')->useCurrent();
            $table->string('table_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
