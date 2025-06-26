<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained('families')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('street', 100);
            $table->string('postcode', 6);
            $table->string('house_number', 4);
            $table->string('addition', 5)->nullable();
            $table->string('city', 50);
            $table->string('mobile', 10);
            $table->string('email', 255);
            $table->string('full_address')->storedAs("CONCAT(street, ' ', house_number, ' ', COALESCE(addition, ''), ', ', postcode, ', ', city)");
            $table->boolean('is_active');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
