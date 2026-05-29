<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselor_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('counselor_id');
            $table->dateTime('session_started_at');
            $table->dateTime('session_ended_at')->nullable();
            $table->text('summary_notes')->nullable(); // Optional case diagnostics space
            $table->timestamps();

            // Core keys map arrays
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_logs');
    }
};
