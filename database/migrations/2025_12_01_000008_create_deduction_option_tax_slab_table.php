<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionOptionTaxSlabTable extends Migration
{
    public function up()
    {
        Schema::create('deduction_option_tax_slab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deduction_option_id');
            $table->unsignedBigInteger('tax_slab_id');
            $table->timestamps();
            $table->foreign('deduction_option_id')->references('id')->on('deduction_options')->onDelete('cascade');
            $table->foreign('tax_slab_id')->references('id')->on('tax_slabs')->onDelete('cascade');
            $table->unique(['deduction_option_id', 'tax_slab_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deduction_option_tax_slab');
    }
}
