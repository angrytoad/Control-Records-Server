<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderTableLineItemTableItemTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('email');
            $table->uuid('customer_id');
            $table->string('method');
            $table->timestamps();
        });

        Schema::create('line_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('order_id');
            $table->uuid('item_id');
            $table->integer('price');
            $table->uuid('item_type_id');
            $table->timestamps();
        });

        Schema::create('item_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('type');
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
        Schema::drop('orders');
        Schema::drop('lines_items');
        Schema::drop('item_types');
    }
}
