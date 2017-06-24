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
        foreach ($this->fixtures() as $item) {
            DB::table('restaurants')->insert($item);
        }
    }

    private function fixtures()
    {
        return [
            [
                'name' => 'Goethe',
                'description' => 'Mahlzeit! Thai dishes and spaghetti',
            ],
            [
                'name' => 'Food Court',
                'description' => 'Many restaurants to explore.',
            ],
            [
                'name' => 'Noodle Place',
                'description' =>'Various noodles, some rice dishes.',
            ],
            [
                'name' => 'Sam & Lek',
                'description' => 'A restaurant in a hidden place.',
            ],
            [
                'name' => 'Duck Noodle',
                'description' => 'Duck noodle is great, other dishes even better!',
            ],
            [
                'name' => 'Sun Moon',
                'description' => 'Popular among Chinese.'
            ],
            [
                'name' => 'Sai Sa-ard',
                'description' => 'Great noodle. One dish for dieters.'
            ],
            [
                'name' => 'Che-po',
                'description' => 'Duck noodle and rices',
            ],
            [
                'name' => 'Tree House',
                'description' => 'All terrains restaurant.'
            ],
        ];
    }
}
