<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Mirrors manual_end_date: lets a non-first stage's start date be pinned by
// the user instead of always being chained to the day after the previous
// stage's end date (e.g. a stage is done, but the next one only starts once
// a specific future appointment happens, leaving a gap in between).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->boolean('manual_start_date')->default(false)->after('manual_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->dropColumn('manual_start_date');
        });
    }
};
