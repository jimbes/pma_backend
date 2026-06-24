<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['appointment', 'medication_reminder']);
            $table->unsignedBigInteger('related_entity_id');
            $table->string('subject');
            $table->text('message');
            $table->enum('channel', ['push', 'email'])->default('push');
            $table->longText('push_token')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('failed_reason')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamps();
            $table->index('couple_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('next_retry_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
