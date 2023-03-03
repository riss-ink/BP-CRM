<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('addon_product_category', function (Blueprint $table){
            $table->unsignedInteger('product_category_id');
            $table->unsignedInteger('addon_id');
        });
        Schema::create('addon_product', function (Blueprint $table){
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('addon_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addons');
        Schema::dropIfExists('addon_product_category');
        Schema::dropIfExists('addon_product');


    }
}
