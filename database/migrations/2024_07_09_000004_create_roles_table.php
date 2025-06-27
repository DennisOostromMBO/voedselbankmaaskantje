<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 20);
            $table->boolean('is_active');
            $table->string('comment', 255)->nullable();
            $table->timestamp('date_created', 6);
            $table->timestamp('date_changed', 6);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
