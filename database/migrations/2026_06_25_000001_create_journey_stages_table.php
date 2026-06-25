<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journey_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->foreignId('treatment_cycle_id')->nullable()->constrained('treatment_cycles')->onDelete('cascade');
            $table->enum('type', ['stimulation', 'declenchement', 'ponction', 'transfert', 'attente_test']);
            $table->date('start_date');
            $table->time('start_time')->nullable();
            $table->enum('status', ['upcoming', 'in_progress', 'done'])->default('upcoming');
            $table->boolean('reminder_enabled')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_stages');
    }
};
