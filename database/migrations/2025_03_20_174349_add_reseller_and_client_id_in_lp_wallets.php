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
        Schema::connection('lp_own_db')->table('wallets', function (Blueprint $table) {
            $table->string('reseller_id')->after('transaction_id')->nullable();
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
        Schema::connection('lp_own_db')->table('wallets', function (Blueprint $table) {
            $table->dropColumn('reseller_id');
            $table->dropColumn('end_client_id');
        });
    }
};
