<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->unsignedSmallInteger("name_size");
            $table->unsignedSmallInteger("variations_size");
            $table->unsignedSmallInteger("price_size");
            $table->unsignedSmallInteger("price_type");
            $table->unsignedSmallInteger("business_name_size");
            $table->unsignedSmallInteger("packing_date_size");
            $table->unsignedSmallInteger("lot_number_size");
            $table->unsignedSmallInteger("exp_date_size");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            //
        });
    }
};
