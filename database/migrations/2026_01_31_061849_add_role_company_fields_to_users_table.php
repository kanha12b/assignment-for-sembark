<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['SUPERADMIN', 'ADMIN', 'MEMBER'])
                ->default('MEMBER')
                ->after('password');

            $table->foreignId('company_id')
                ->nullable()
                ->after('role');

            $table->foreignId('created_by')
                ->nullable()
                ->after('company_id');

            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'company_id', 'created_by', 'status']);
    });
    }
};
