<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time')->nullable();
            $table->string('location')->nullable();
            $table->string('doctor_name')->nullable();
            $table->boolean('notify_user_1')->default(true);
            $table->boolean('notify_user_2')->default(true);
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->index('couple_id');
            $table->index('appointment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
