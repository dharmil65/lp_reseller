<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('reseller_id')->nullable();
            $table->integer('end_client_id')->nullable();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('order_id')->unique()->nullable();
            $table->string('credit_or_debit')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->float('total', 10, 2)->nullable();
            $table->string('provider')->nullable();
            $table->string('status')->nullable();
            $table->string('tax')->nullable();
            $table->string('finalamount')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('paypal_fee')->nullable();
            $table->integer('order_attribute_id')->nullable();
            $table->string('order_lable')->nullable();
            $table->text('description')->nullable();
            $table->string('order_type')->nullable();
            $table->integer('first_payment_flag')->default(0);
            $table->string('clickedFlag')->nullable();
            $table->string('added_desc')->nullable();
            $table->string('added_by_user_id')->nullable();
            $table->string('table_type')->nullable()->comment('fund or order table');
            $table->string('admin_credit_debit')->nullable();
            $table->string('adv_discount')->nullable();
            $table->string('new_code_update')->default(1)->comment('code change for payment with bifurcation');
            $table->integer('project_id')->nullable();
            $table->string('free_trial')->default(0)->comment('if 1 Then free trial is running');
            $table->string('is_reseller')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};