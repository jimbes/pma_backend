<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['medication_reminder', 'appointment_reminder', 'journey_stage_reminder']);
            $table->enum('channel', ['push', 'email', 'both'])->default('both');
            $table->boolean('enabled')->default(true);
            $table->integer('reminder_minutes_before')->default(15);
            $table->timestamps();

            // Ensure unique preference per user per type
            $table->unique(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
