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
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reseller_id')->nullable();
            $table->integer('advertiser_id')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('content_writter')->nullable();
            $table->string('instruction')->nullable();
            $table->string('title')->nullable();
            $table->integer('expert_price')->nullable();
            $table->string('keyword')->nullable();
            $table->string('reference')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('brief_note')->nullable();
            $table->string('attachment')->nullable();
            $table->float('price',10,2)->nullable();
            $table->float('total',10,2)->nullable();
            $table->integer('expert_price_id')->nullable();
            $table->string('choose_content')->nullable();
            $table->string('writting_style')->nullable();
            $table->string('preferred_voice')->nullable();
            $table->string('refrence_link')->nullable(); 
            $table->string('anchor_text')->nullable(); 
            $table->string('blog_url')->nullable(); 
            $table->integer('link_insertion_price')->nullable(); 
            $table->integer('other_category_price')->nullable();
            $table->boolean('price_changed')->default(0);
            $table->string('tag_used')->nullable();
            $table->integer('quantity_no')->default(1); 
            $table->string('prefered_language')->nullable();
            $table->string('target_audience')->nullable();
            $table->integer('marketplace_type')->nullable(); 
            $table->integer('wihthout_commission_guest_post_price')->nullable();
            $table->integer('wihthout_commission_linkinsertion_price')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('source')->nullable();
            $table->string('anchor_text_1')->nullable();
            $table->string('anchor_text_2')->nullable();
            $table->string('anchor_text_3')->nullable();
            $table->string('anchor_text_4')->nullable();
            $table->string('target_url_1')->nullable();
            $table->string('target_url_2')->nullable();
            $table->string('target_url_3')->nullable();
            $table->string('target_url_4')->nullable();
            $table->string('language')->nullable();
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
        Schema::dropIfExists('carts');
    }
};