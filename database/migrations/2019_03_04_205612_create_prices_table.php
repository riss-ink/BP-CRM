<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('priceable_id')->default(0);
            $table->string('priceable_type')->default('');
            $table->unsignedInteger('term_id')->default(0);
            $table->decimal('set_up_amount', 13, 2)->default(0);
            $table->decimal('recurring_amount', 13, 2)->default(0);
            $table->boolean('quote');
            $table->timestamps();
        });
/*        Schema::create('product_price', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('price_id');
        });
        Schema::create('addon_price', function (Blueprint $table) {
            $table->unsignedInteger('addon_id');
            $table->unsignedInteger('price_id');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');



    }
}
