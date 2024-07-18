<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('item_name', 100);
            $table->decimal('item_price', 10, 2);
            $table->text('item_description')->nullable();
            $table->string('item_label', 50);
            $table->string('item_type', 50);
            $table->string('image_url', 255)->nullable(); // Menambahkan kolom untuk menyimpan URL atau path gambar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
}
