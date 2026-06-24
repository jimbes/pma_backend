<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medication_taken_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_schedule_id')->constrained('medication_schedules')->onDelete('cascade');
            $table->date('date');
            $table->boolean('taken')->default(false);
            $table->time('taken_at')->nullable();
            $table->foreignId('user_logged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['medication_schedule_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_taken_log');
    }
};
