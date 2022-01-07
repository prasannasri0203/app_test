<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowchartProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flowchart_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('description')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('editor_id')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('viewer_id')->nullable();
            $table->integer('created_by');
            $table->tinyInteger('status')->comment('1-active,0-inactive')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('user_templates', function($table) {
            $table->dropColumn('to_editor');
            $table->dropColumn('to_approver');
            $table->integer('project_id')->nullable()->after('status');
            $table->integer('template_id')->nullable()->after('project_id');
            $table->string('rejected_reason')->nullable()->after('editor_status');
        });
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flowchart_projects');
    }
}
