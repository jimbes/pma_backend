<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The store()/update() validation in JourneyStageController has always
// accepted 'preparation' and 'controle' as valid types, but the original
// table migration's ENUM never included them - submitting either value
// passes validation then fails with a raw SQL error at the DB layer.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE journey_stages MODIFY type ENUM('preparation', 'stimulation', 'controle', 'declenchement', 'ponction', 'transfert', 'attente_test') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE journey_stages MODIFY type ENUM('stimulation', 'declenchement', 'ponction', 'transfert', 'attente_test') NOT NULL");
    }
};
