<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayslipTypeIdToTaxSlabs extends Migration
{
    public function up()
    {
        Schema::table('tax_slabs', function (Blueprint $table) {
            if (!Schema::hasColumn('tax_slabs', 'payslip_type_id')) {
                $table->unsignedBigInteger('payslip_type_id')->nullable()->after('created_by');
            }
        });
    }

    public function down()
    {
        Schema::table('tax_slabs', function (Blueprint $table) {
            if (Schema::hasColumn('tax_slabs', 'payslip_type_id')) {
                $table->dropColumn('payslip_type_id');
            }
        });
    }
}
