<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The cycle's start_date used to be set once, whenever the cycle row was
// created (often before any stage existed) - it was never kept in sync with
// the first stage's actual start date, causing the app to show an
// off-by-N "J{n}" on day 1 of stage 1. Going forward JourneyStage::booted()
// keeps them in sync; this backfills existing cycles once.
return new class extends Migration
{
    public function up(): void
    {
        $firstStages = DB::table('journey_stages')
            ->where('order', 0)
            ->select('treatment_cycle_id', 'start_date')
            ->get();

        foreach ($firstStages as $stage) {
            DB::table('treatment_cycles')
                ->where('id', $stage->treatment_cycle_id)
                ->update(['start_date' => $stage->start_date]);
        }
    }

    public function down(): void
    {
        // Data backfill - the previous start_date values weren't meaningful
        // (they weren't tied to any stage), so there's nothing to restore.
    }
};
