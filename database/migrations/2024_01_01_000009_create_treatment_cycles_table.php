<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('treatment_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->integer('cycle_number')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['in_progress', 'failed', 'succeeded'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('couple_id');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_cycles');
    }
};
