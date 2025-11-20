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
        // Allowance options already has all fields, skip

        // Add missing columns to deduction_options
        Schema::table('deduction_options', function (Blueprint $table) {
            if (!Schema::hasColumn('deduction_options', 'amount')) {
                $table->decimal('amount', 15, 2)->nullable()->after('type');
            }
            if (!Schema::hasColumn('deduction_options', 'min_amount')) {
                $table->decimal('min_amount', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('deduction_options', 'max_amount')) {
                $table->decimal('max_amount', 15, 2)->nullable()->after('min_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allowance_options', function (Blueprint $table) {
            $table->dropColumn(['type', 'amount', 'min_amount', 'max_amount']);
        });

        Schema::table('deduction_options', function (Blueprint $table) {
            $table->dropColumn(['type', 'amount', 'min_amount', 'max_amount']);
        });
    }
};
