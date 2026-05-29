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
        Schema::create('human_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('token')->unique();
            $table->string('alias')->default('Anonymous Guest');
            $table->boolean('is_flagged')->default(false);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_conversations');
    }
};
