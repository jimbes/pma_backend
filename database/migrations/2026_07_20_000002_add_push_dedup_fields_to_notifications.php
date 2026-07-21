<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Lets the push payload carry enough identity for the client to compute the
// exact same deterministic Android notification id it uses for its own
// locally-scheduled reminder (see notification_id.dart /
// NotificationService::medicationReminderNotificationId) - so whichever of
// the two (local alarm, push) fires second replaces the first in the
// notification tray instead of showing a duplicate.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedInteger('offset_minutes')->nullable()->after('scheduled_for');
            // "HH:mm" - the dose/appointment time itself (distinct from
            // scheduled_for, which is dose time minus offset_minutes).
            // Medication reminders only.
            $table->string('dose_time', 5)->nullable()->after('offset_minutes');
            // 0=Monday..6=Sunday - specific_days medication reminders only.
            $table->unsignedTinyInteger('weekday')->nullable()->after('dose_time');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['offset_minutes', 'dose_time', 'weekday']);
        });
    }
};
