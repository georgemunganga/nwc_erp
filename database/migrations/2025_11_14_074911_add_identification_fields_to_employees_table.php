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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nrc_number')->nullable()->after('man_number');
            $table->string('drivers_license_number')->nullable()->after('nrc_number');
            $table->string('passport_number')->nullable()->after('drivers_license_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['nrc_number', 'drivers_license_number', 'passport_number']);
        });
    }
};
