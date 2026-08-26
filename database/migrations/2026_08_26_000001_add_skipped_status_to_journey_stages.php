<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// A stage can now be explicitly skipped (couple didn't go through it,
// e.g. no antagonist checkpoint needed) instead of only upcoming/in_progress/done.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE journey_stages MODIFY status ENUM('upcoming', 'in_progress', 'done', 'skipped') NOT NULL DEFAULT 'upcoming'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE journey_stages MODIFY status ENUM('upcoming', 'in_progress', 'done') NOT NULL DEFAULT 'upcoming'");
    }
};
