<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('type', ['echo', 'blood_test', 'consult', 'ponction', 'transfert', 'other'])->nullable()->after('appointment_time');
            $table->integer('reminder_minutes_before')->default(60)->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['type', 'reminder_minutes_before']);
        });
    }
};
