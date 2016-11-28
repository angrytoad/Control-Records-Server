<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Provider\Uuid;
use Carbon\Carbon;

class AddItemTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_types')->insert([
            'id' => Uuid::uuid(),
            'type' => 'song',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('item_types')->insert([
            'id' => Uuid::uuid(),
            'type' => 'album',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('item_types')->insert([
            'id' => Uuid::uuid(),
            'type' => 'other',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
