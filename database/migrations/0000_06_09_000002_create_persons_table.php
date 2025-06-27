<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 60);
            $table->string('infix', 40)->nullable();
            $table->string('last_name', 50);
            $table->string('full_name')->storedAs("CONCAT(first_name, ' ', IFNULL(infix, ''), ' ', last_name)");
            $table->unsignedTinyInteger('age');
            $table->boolean('is_active');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
