<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('lp_own_db')->table('order_attributes', function (Blueprint $table) {
            $table->boolean('reseller_order')->default(0);
            $table->string('reseller_order_lable')->after('order_id')->nullable();
            $table->string('reseller_id')->after('reseller_order_lable')->nullable();
            $table->string('end_client_id')->after('reseller_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('lp_own_db')->table('order_attributes', function (Blueprint $table) {
            $table->dropColumn('reseller_order');
            $table->dropColumn('reseller_order_lable');
            $table->dropColumn('reseller_id');
            $table->dropColumn('end_client_id');
        });
    }
};
