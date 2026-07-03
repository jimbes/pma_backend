<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medication_taken_log', function (Blueprint $table) {
            // A schedule with several reminder times a day (e.g. 08:00 and
            // 20:00) previously shared one log row per day, so checking one
            // dose checked all of them. Nullable so existing rows (and any
            // future single-time-per-day case) stay valid - MySQL doesn't
            // treat NULLs as equal for uniqueness, so multiple NULL-time
            // rows per day/schedule remain allowed too.
            $table->string('time', 5)->nullable()->after('date');
        });

        Schema::table('medication_taken_log', function (Blueprint $table) {
            $table->dropUnique(['medication_schedule_id', 'date']);
            $table->unique(['medication_schedule_id', 'date', 'time']);
        });
    }

    public function down(): void
    {
        Schema::table('medication_taken_log', function (Blueprint $table) {
            $table->dropUnique(['medication_schedule_id', 'date', 'time']);
            $table->unique(['medication_schedule_id', 'date']);
            $table->dropColumn('time');
        });
    }
};
