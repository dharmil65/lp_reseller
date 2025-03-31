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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('reseller_id')->nullable();
            $table->integer('end_client_id')->nullable();
            $table->string('order_attribute_id')->nullable();
            $table->string('order_lable')->nullable();
            $table->boolean('reseller_seen')->default(0);
            $table->boolean('client_seen')->default(0);
            $table->string('type')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('notifications');
    }
};
