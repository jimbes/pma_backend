<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // The exact reminder instant this notification is for (e.g. a
            // specific dose time on a specific day, or an appointment's
            // reminder instant). Without this, the 15-minute cron re-creates
            // a notification every run for the rest of the day once a
            // reminder time has passed - the unique index below makes
            // firstOrCreate() an atomic dedup instead.
            $table->timestamp('scheduled_for')->nullable()->after('related_entity_id');
            $table->unique(
                ['user_id', 'type', 'related_entity_id', 'scheduled_for'],
                'notifications_dedup_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropUnique('notifications_dedup_unique');
            $table->dropColumn('scheduled_for');
        });
    }
};
