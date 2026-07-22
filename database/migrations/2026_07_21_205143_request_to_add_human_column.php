<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The migrations table already has a row for
     * '2026_05_29_233142_add_is_human_request_to_conversations_table',
     * but the column was never actually created (likely because a SQL
     * dump was imported that predates that migration's schema change,
     * while still carrying its history row). This migration re-adds
     * the column safely, checking first so it never errors if it
     * somehow already exists.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('conversations', 'is_human_request')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->boolean('is_human_request')->default(false)->after('risk_level');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('conversations', 'is_human_request')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->dropColumn('is_human_request');
            });
        }
    }
};
