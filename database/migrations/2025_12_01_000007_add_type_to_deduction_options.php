<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToDeductionOptions extends Migration
{
    public function up()
    {
        Schema::table('deduction_options', function (Blueprint $table) {
            if (!Schema::hasColumn('deduction_options', 'type')) {
                $table->string('type')->default('default_salary_tax_slab')->after('name');
            }
        });
    }

    public function down()
    {
        Schema::table('deduction_options', function (Blueprint $table) {
            if (Schema::hasColumn('deduction_options', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
