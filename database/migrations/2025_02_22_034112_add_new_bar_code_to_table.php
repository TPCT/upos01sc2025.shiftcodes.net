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
            $table->string('type')->nullable();
        });
        \App\Barcode::create([
            'name' => 'Double Labels For One Sticker (1.25 * 3.8)',
            'description' => 'Double Labels For One Sticker (1.25 * 3.8)',
            'width' => 0.9,
            'height' => 0.95,
            'paper_width' => 1.57,
            'paper_height' => 0.99,
            'top_left' => 0,
            'left_margin' => 0,
            'row_distance' => 0,
            'col_distance' => 0,
            'stickers_in_one_row' => 1,
            'is_default' => 0,
            'is_continuous' => 0,
            'stickers_in_one_sheet' => 2,
            'business_id' => null,
            'name_size' => 6.0,
            'variations_size' => 0,
            'price_size' => 8.5,
            'price_type' => 0,
            'business_name_size' => 6.8,
            'packing_date_size' => 0,
            'lot_number_size' => 0,
            'exp_date_size' => 6.8,
            'type' => 'double-labels-for-one-sticker'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
