<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('type');
            $table->boolean('manual_end_date')->default(false)->after('duration_days');
        });
    }

    public function down(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->dropColumn(['custom_name', 'manual_end_date']);
        });
    }
};
