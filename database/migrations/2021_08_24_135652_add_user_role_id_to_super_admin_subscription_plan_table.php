<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserRoleIdToSuperAdminSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('super_admin_subscription_plan', function (Blueprint $table) {
            $table->integer('user_role_id')->after('id');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('super_admin_subscription_plan', function (Blueprint $table) {
            $table->dropColumn('user_role_id');
        });
    }
}
