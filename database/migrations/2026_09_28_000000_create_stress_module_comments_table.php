<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stress_module_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stress_module_id')->constrained()->cascadeOnDelete();
            $table->string('author_alias');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stress_module_comments');
    }
};
