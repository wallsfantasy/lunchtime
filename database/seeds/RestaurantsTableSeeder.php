<?php

use Illuminate\Database\Seeder;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('restaurants')->insert(static::fixtures());
    }

    public static function fixtures()
    {
        return [
            [
                'id' => 1,
                'name' => 'Goethe',
                'description' => 'Mahlzeit! Thai dishes and spaghetti',
            ],
            [
                'id' => 2,
                'name' => 'Food Court',
                'description' => 'Many restaurants to explore.',
            ],
            [
                'id' => 3,
                'name' => 'Noodle Place',
                'description' => 'Various noodles, some rice dishes.',
            ],
            [
                'id' => 4,
                'name' => 'Sam & Lek',
                'description' => 'A restaurant in a hidden place.',
            ],
            [
                'id' => 5,
                'name' => 'Duck Noodle',
                'description' => 'Duck noodle is great, other dishes even better!',
            ],
            [
                'id' => 6,
                'name' => 'Sun Moon',
                'description' => 'Popular among Chinese.'
            ],
            [
                'id' => 7,
                'name' => 'Sai Sa-ard',
                'description' => 'Great noodle. One dish for dieters.'
            ],
            [
                'id' => 8,
                'name' => 'Che-po',
                'description' => 'Duck noodle and rices',
            ],
            [
                'id' => 9,
                'name' => 'Tree House',
                'description' => 'All terrains restaurant.'
            ],
        ];
    }
}
