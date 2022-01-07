<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_users', function (Blueprint $table) {
            $table->id(); 
            $table->integer('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('contact_no');
            $table->string('organization_name');   
            $table->integer('user_role_id'); 
            $table->integer('parent_id')->default(0); 
            $table->integer('is_approved');             
            $table->integer('location_id');             
            $table->integer('team_count')->default(0);             
            $table->integer('plan_id')->default(0);             
             $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('status')->default(1); 
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
        Schema::dropIfExists('deleted_users');
    }
}
