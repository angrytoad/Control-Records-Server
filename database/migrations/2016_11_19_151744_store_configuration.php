<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_configurations', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('configuration_name');
            $table->boolean('configuration_active');
            $table->string('featured_article');
            $table->string('featured_album');
            $table->string('featured_artist');
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
        Schema::drop('store_configurations');
    }
}
