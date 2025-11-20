<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSsnAndManNumberToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'ssn')) {
                $table->string('ssn')->nullable()->after('tax_payer_id');
            }
            if (!Schema::hasColumn('employees', 'man_number')) {
                $table->string('man_number')->nullable()->after('ssn');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'man_number')) {
                $table->dropColumn('man_number');
            }
            if (Schema::hasColumn('employees', 'ssn')) {
                $table->dropColumn('ssn');
            }
        });
    }
}
