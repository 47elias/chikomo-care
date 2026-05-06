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
        Schema::create('counselor_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained();
            $table->foreignId('conversation_id')->constrained(); // Links to the 'conversations' table
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselor_assignments');
    }
};
