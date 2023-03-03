<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);

            $table->string('name')->nullable();
            $table->string('current_url')->nullable();
            $table->text('objectives')->nullable();
            $table->string('email')->nullable();
            $table->text('difference')->nullable();
            $table->json('keywords')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->length(2)->nullable();
            $table->string('zip')->length(5)->nullable();
            $table->string('phone')->nullable();
            $table->json('business_hours')->nullable();
            $table->json('geo_targeting')->nullable();
            $table->json('competitors')->nullable();
            $table->json('contacts')->nullable();
            $table->string('ga_ua_code')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->unsigned()->default(\App\Enums\ClientStatus::Active);
            $table->date('canceled_at')->nullable();
            $table->boolean('google_tag_manager_access')->default(0);

            $table->timestamps();
        });
        Schema::table('clients', function (Blueprint $table) {
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
        Schema::dropIfExists('clients');
    }
}
