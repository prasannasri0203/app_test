<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('is_own')->default(0)->after('status')->comment = "1=own; 0=superadmin";
            $table->integer('parent_id')->default(0)->after('is_own');
            $table->integer('is_approved')->default(0)->after('parent_id')->comment = "1=approved; 0=pending; 2=rejected";
            $table->string('reason')->nullable()->after('is_approved');
            $table->integer('location_id')->default(0)->after('reason');
            $table->integer('team_count')->default(0)->after('location_id');
            $table->integer('plan_id')->default(0)->after('team_count');
            $table->integer('theme_setting_id')->default(0)->after('plan_id');
            $table->dateTime('login_at')->after('theme_setting_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
