<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxSlabToSaturationDeductions extends Migration
{
    public function up()
    {
        Schema::table('saturation_deductions', function (Blueprint $table) {
            if (!Schema::hasColumn('saturation_deductions', 'tax_slab_id')) {
                $table->unsignedBigInteger('tax_slab_id')->nullable()->after('type');
            }
        });
    }

    public function down()
    {
        Schema::table('saturation_deductions', function (Blueprint $table) {
            if (Schema::hasColumn('saturation_deductions', 'tax_slab_id')) {
                $table->dropColumn('tax_slab_id');
            }
        });
    }
}
