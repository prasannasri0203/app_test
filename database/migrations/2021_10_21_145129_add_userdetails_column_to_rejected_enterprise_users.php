<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserdetailsColumnToRejectedEnterpriseUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rejected_enterprise_users', function (Blueprint $table) {
             $table->string('contact_no')->nullable();
             $table->string('organization_name')->nullable();
             $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rejected_enterprise_users', function (Blueprint $table) {
            //
        });
    }
}
