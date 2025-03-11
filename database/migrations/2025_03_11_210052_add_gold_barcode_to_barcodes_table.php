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
        \App\Barcode::create([
            'name' => 'Gold Tag Barcode',
            'description' => 'Gold Tag Barcode 8.2cm * 3.7cm',
            'width' => 1.8,
            'height' => 0.9,
            'paper_width' => 8.2,
            'paper_height' => 3.7,
            'type' => 'gold-tag-barcode',
            'stickers_in_one_sheet' => 1,
            'name_size' => 6,
            'price_size' => 6,
            'variations_size' => 6
        ]);
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
