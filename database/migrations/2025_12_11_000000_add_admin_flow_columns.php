<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            if (!Schema::hasColumn('debts', 'approval_status')) {
                $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            }
            if (!Schema::hasColumn('debts', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('debts', 'rejected_reason')) {
                $table->text('rejected_reason')->nullable()->after('approved_at');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('note');
            }
            if (!Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            if (Schema::hasColumn('debts', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
            if (Schema::hasColumn('debts', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('debts', 'rejected_reason')) {
                $table->dropColumn('rejected_reason');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
            if (Schema::hasColumn('payments', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
        });
    }
};
