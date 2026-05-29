<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Track matchmaking triage parameters
            if (!Schema::hasColumn('conversations', 'counselor_id')) {
                $table->unsignedBigInteger('counselor_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('conversations', 'status')) {
                // pending = in queue, active = currently chatting, completed = session closed
                $table->string('status')->default('pending')->after('risk_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['counselor_id', 'status']);
        });
    }
};
