<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePaidAmountColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('super_admin_subscription_plan', function (Blueprint $table) {
          $table->renameColumn('paid_amount', 'amount');
          $table->renameColumn('based_on', 'payment_type');
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
            //
        });
    }
}
