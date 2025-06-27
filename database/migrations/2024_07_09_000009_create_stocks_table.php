<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('cascade');
            $table->boolean('is_active');
            $table->text('note')->nullable();
            $table->date('received_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->string('unit', 50)->nullable();
            $table->unsignedInteger('quantity_in_stock')->default(0);
            $table->unsignedInteger('quantity_delivered')->default(0);
            $table->unsignedInteger('quantity_supplied')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
