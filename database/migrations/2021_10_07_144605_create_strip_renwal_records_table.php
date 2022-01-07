<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripRenwalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strip_renwal_records', function (Blueprint $table) {
            $table->id();
            $table->integer('user_plan_id');
            $table->integer('user_id');
            $table->integer('user_role_id');
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_coupon_id')->nullable();
            $table->string('stripe_tax_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subcription_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_card_id')->nullable();
            $table->string('stripe_response')->nullable();
            $table->string('stripe_status')->nullable();
            $table->integer('stripe_payment_collection_status')->default(0);
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
        Schema::dropIfExists('strip_renwal_records');
    }
}
