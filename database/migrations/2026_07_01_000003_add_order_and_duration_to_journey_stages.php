<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0)->after('couple_id');
            $table->unsignedInteger('duration_days')->nullable()->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('journey_stages', function (Blueprint $table) {
            $table->dropColumn(['order', 'duration_days']);
        });
    }
};
