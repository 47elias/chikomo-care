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
        Schema::create('peer_stories', function (Blueprint $table) {
            $table->id();
            $table->string('author_alias')->default('Anonymous Peer'); // Keeps user records hidden
            $table->string('title');
            $table->text('content');
            $table->boolean('is_approved')->default(0); // Triage status (0 = Pending Review, 1 = Approved)
            $table->decimal('rating_average', 3, 2)->default(0.00); // Dynamic evaluation calculations
            $table->integer('total_ratings_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peer_stories');
    }
};
