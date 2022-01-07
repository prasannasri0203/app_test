<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');            
            $table->string('description')->nullable();
            $table->integer('coupon_id')->default(0);
            $table->integer('is_activate')->default(0);
            $table->date('renewal_date');
            $table->double('amount', 8, 2)->default(0);
            $table->integer('payment_type')->default(0)->comment = "1=cash; 2=cheque; 3=online";
            $table->longText('transaction_id')->nullable();
            $table->integer('status')->default(0)->comment = "1=success; 2=failed";
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
        Schema::dropIfExists('renewal_details');
    }
}
