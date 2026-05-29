<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('human_counselor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('human_conversation_id')->constrained('human_conversations')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('session_started_at')->useCurrent();
            $table->timestamp('session_ended_at')->nullable();
            $table->text('summary_notes')->nullable();
            $table->timestamps();

            $table->index(['counselor_id', 'session_ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_counselor_logs');
    }
};
