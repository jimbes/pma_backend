<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// treatment_cycles has existed since the journey_stages table was created but
// was never populated (no controller/route ever wrote to it) - every stage
// so far has a null treatment_cycle_id. Now that "start a new cycle" becomes
// a real action, every couple needs a well-defined "current" cycle so their
// existing stages aren't orphaned once index()/store() start scoping by it.
return new class extends Migration
{
    public function up(): void
    {
        $coupleIds = DB::table('journey_stages')
            ->whereNull('treatment_cycle_id')
            ->distinct()
            ->pluck('couple_id');

        foreach ($coupleIds as $coupleId) {
            $earliestStart = DB::table('journey_stages')
                ->where('couple_id', $coupleId)
                ->whereNull('treatment_cycle_id')
                ->min('start_date');

            $cycleId = DB::table('treatment_cycles')->insertGetId([
                'couple_id' => $coupleId,
                'cycle_number' => 1,
                'start_date' => $earliestStart ?? now()->toDateString(),
                'status' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('journey_stages')
                ->where('couple_id', $coupleId)
                ->whereNull('treatment_cycle_id')
                ->update(['treatment_cycle_id' => $cycleId]);
        }
    }

    public function down(): void
    {
        // Data backfill - not reversible without losing the cycle grouping.
    }
};
