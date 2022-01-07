<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowchartMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flowchart_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_template_id')->default(0);
            $table->integer('project_id')->default(0);
            $table->integer('mapped_flowchart_id')->default(0);
            $table->integer('order_number')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flowchart_mappings');
    }
}
