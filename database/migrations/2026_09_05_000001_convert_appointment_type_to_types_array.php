<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// A single appointment can now cover more than one subject (e.g. an écho
// and a prise de sang on the same visit) - replaces the single `type` enum
// with a `types` JSON array. Existing appointments keep their one subject,
// just wrapped in a list.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->json('types')->nullable()->after('type');
        });

        $appointments = DB::table('appointments')->select('id', 'type')->get();
        foreach ($appointments as $appointment) {
            $types = $appointment->type !== null ? [$appointment->type] : [];
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['types' => json_encode($types)]);
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('type', ['echo', 'blood_test', 'consult', 'ponction', 'transfert', 'other'])->nullable()->after('appointment_time');
        });

        $appointments = DB::table('appointments')->select('id', 'types')->get();
        foreach ($appointments as $appointment) {
            $types = json_decode($appointment->types ?? '[]', true);
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['type' => $types[0] ?? null]);
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('types');
        });
    }
};
