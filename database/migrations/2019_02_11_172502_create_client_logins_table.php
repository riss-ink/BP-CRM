<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->default(0);
            $table->string('url')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('client_logins');
    }
}
