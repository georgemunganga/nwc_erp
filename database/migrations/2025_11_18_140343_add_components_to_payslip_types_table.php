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
        // Create pivot table for payslip_type and allowance_option
        Schema::create('payslip_type_allowance_option', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payslip_type_id');
            $table->unsignedBigInteger('allowance_option_id');
            $table->timestamps();

            $table->foreign('payslip_type_id')->references('id')->on('payslip_types')->onDelete('cascade');
            $table->foreign('allowance_option_id')->references('id')->on('allowance_options')->onDelete('cascade');

            $table->unique(['payslip_type_id', 'allowance_option_id'], 'payslip_allowance_unique');
        });

        // Create pivot table for payslip_type and deduction_option
        Schema::create('payslip_type_deduction_option', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payslip_type_id');
            $table->unsignedBigInteger('deduction_option_id');
            $table->timestamps();

            $table->foreign('payslip_type_id')->references('id')->on('payslip_types')->onDelete('cascade');
            $table->foreign('deduction_option_id')->references('id')->on('deduction_options')->onDelete('cascade');

            $table->unique(['payslip_type_id', 'deduction_option_id'], 'payslip_deduction_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_type_deduction_option');
        Schema::dropIfExists('payslip_type_allowance_option');
    }
};
