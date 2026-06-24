<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medication_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained('medications')->onDelete('cascade');
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('frequency', ['daily', 'specific_days'])->default('daily');
            $table->json('days_of_week')->nullable();
            $table->json('reminder_times');
            $table->integer('reminder_offset_hours')->default(1);
            $table->boolean('notify_user_1')->default(true);
            $table->boolean('notify_user_2')->default(true);
            $table->timestamps();
            $table->index('couple_id');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};
