<?php

use Illuminate\Database\Seeder;
use App\Item_head;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ItemHeadsTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $faker = Faker\Factory::create();

        foreach (range(1, 5) as $index) {
        	App\Item_head::create([
        		'user_id' => $faker->numberBetween($min = 1, $max = 5)
        	]);
        }
    }
}
