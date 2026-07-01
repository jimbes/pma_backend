<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medication_schedules', function (Blueprint $table) {
            $table->foreignId('journey_stage_id')->nullable()->constrained('journey_stages')->onDelete('set null')->after('couple_id');
        });
    }

    public function down(): void
    {
        Schema::table('medication_schedules', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['journey_stage_id']);
            $table->dropColumn('journey_stage_id');
        });
    }
};
