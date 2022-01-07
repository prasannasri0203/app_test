<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('file_name')->nullable();
            $table->string('template_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('status')->default(0)->comment = "1=active; 0=inactive";
            $table->integer('editor_review')->default(0)->comment = "1=if editor requested changes has been done by creator";
            $table->integer('to_editor')->default(0);
            $table->integer('editor_status')->default(0)->comment = "1=Pending for approval; 2=Request for change; 3=Achieved; 4=Declined";
            $table->integer('to_approver')->default(0);
            $table->integer('is_approved')->default(0)->comment = "1=approved; 2=rejected";
            $table->integer('updated_by')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('user_templates');
    }
}
