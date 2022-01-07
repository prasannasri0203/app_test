<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFontSizeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theme_settings', function (Blueprint $table) {
            $table->renameColumn('color_code', 'background_color');
            $table->renameColumn('font_size', 'font_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theme_settings', function (Blueprint $table) {
            //
        });
    }
}
