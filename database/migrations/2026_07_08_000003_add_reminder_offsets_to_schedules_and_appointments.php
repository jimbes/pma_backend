<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medication_schedules', function (Blueprint $table) {
            // A dose can now have several reminders (e.g. 1h before AND
            // 15min before), not just one - so this replaces the single
            // reminder_offset_hours int with a JSON array of minutes.
            $table->json('reminder_offsets')->nullable()->after('reminder_times');
        });

        foreach (DB::table('medication_schedules')->select('id', 'reminder_offset_hours')->get() as $row) {
            DB::table('medication_schedules')
                ->where('id', $row->id)
                ->update(['reminder_offsets' => json_encode([$row->reminder_offset_hours ?? 15])]);
        }

        Schema::table('medication_schedules', function (Blueprint $table) {
            $table->dropColumn('reminder_offset_hours');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->json('reminder_offsets')->nullable()->after('reminder_minutes_before');
        });

        foreach (DB::table('appointments')->select('id', 'reminder_minutes_before')->get() as $row) {
            DB::table('appointments')
                ->where('id', $row->id)
                ->update(['reminder_offsets' => json_encode([$row->reminder_minutes_before ?? 60])]);
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('reminder_minutes_before');
        });
    }

    public function down(): void
    {
        Schema::table('medication_schedules', function (Blueprint $table) {
            $table->integer('reminder_offset_hours')->default(15)->after('reminder_times');
        });
        foreach (DB::table('medication_schedules')->select('id', 'reminder_offsets')->get() as $row) {
            $offsets = json_decode($row->reminder_offsets ?? '[15]', true);
            DB::table('medication_schedules')
                ->where('id', $row->id)
                ->update(['reminder_offset_hours' => $offsets[0] ?? 15]);
        }
        Schema::table('medication_schedules', function (Blueprint $table) {
            $table->dropColumn('reminder_offsets');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('reminder_minutes_before')->default(60)->after('reminder_offsets');
        });
        foreach (DB::table('appointments')->select('id', 'reminder_offsets')->get() as $row) {
            $offsets = json_decode($row->reminder_offsets ?? '[60]', true);
            DB::table('appointments')
                ->where('id', $row->id)
                ->update(['reminder_minutes_before' => $offsets[0] ?? 60]);
        }
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('reminder_offsets');
        });
    }
};
