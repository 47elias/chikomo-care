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
        Schema::create('counselors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
    $table->string('specialization');
    $table->string('license_number')->unique();
    $table->text('bio')->nullable();
    $table->string('phone_number')->nullable();
    $table->enum('status', ['available', 'busy', 'offline'])->default('offline');
    $table->integer('experience_years')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
