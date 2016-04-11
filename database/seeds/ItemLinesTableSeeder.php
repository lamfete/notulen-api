<?php

use Illuminate\Database\Seeder;
use App\Item_line;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ItemLinesTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $faker = Faker\Factory::create();

        foreach (range(1, 30) as $index) {
        	App\Item_line::create([
        		'item_head_id' => $faker->numberBetween($min = 1, $max = 5),
        		'item_name' => $faker->sentence($nbWords = 6, $variableNbWords = true)
        	]);
        }
    }
}
