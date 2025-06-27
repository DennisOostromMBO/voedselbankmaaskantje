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
            $table->date('ontvangdatum')->nullable();
            $table->date('uigeleverddatum')->nullable();
            $table->string('eenheid', 50)->nullable();
            $table->unsignedInteger('aantalOpVoorad')->default(0);
            $table->unsignedInteger('aantalUigegeven')->default(0);
            $table->unsignedInteger('aantalBijgeleverd')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
