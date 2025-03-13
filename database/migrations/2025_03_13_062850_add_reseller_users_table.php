<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reseller_users', function (Blueprint $table) {
            $table->id();
            $table->string('reseller_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->rememberToken()->nullable();
            $table->string('added_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('active_status')->default(1);
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('std_code')->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('funds_added', 10, 2)->default(0);
            $table->decimal('orders_amount', 10, 2)->default(0);
            $table->decimal('refund_amount', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reseller_users');
    }
};