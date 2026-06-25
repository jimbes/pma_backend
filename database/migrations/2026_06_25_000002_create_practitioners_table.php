<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practitioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->string('name');
            $table->string('specialty')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('clinic_name')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practitioners');
    }
};
