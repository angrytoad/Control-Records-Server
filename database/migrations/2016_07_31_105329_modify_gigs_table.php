<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn('venue_name');
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_email');
            $table->dropColumn('contact_telephone');
            $table->dropColumn('coordinates');
            $table->string('venue');
            $table->string('band');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->string('venue_name');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_telephone');
            $table->json('coordinates');
            $table->dropColumn('venue');
            $table->dropColumn('band');
        });
    }
}
