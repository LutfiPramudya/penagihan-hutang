<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->unique()->after('phone');
            }
            if (!Schema::hasColumn('customers', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('customers', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('customers', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('customers', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });
    }
};
