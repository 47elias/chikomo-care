<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Conversations: The session container
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique(); // The unique session ID
            $table->string('alias'); // Generated name like "Brave Shield"
            $table->boolean('is_flagged')->default(false); // For human analytics
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->timestamps();
        });

        // 2. Messages: The actual chat logs
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->enum('sender_type', ['user', 'ai', 'moderator']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
