<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAdminSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_admin_subscription_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan_name');
            $table->string('plan_type');
            $table->string('activation_period')->nullable();
            $table->smallInteger('display_in_site');
            $table->smallInteger('status');
            $table->string('paid_amount')->nullable();
            $table->string('based_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_admin_subscription_plan');
    }
}
