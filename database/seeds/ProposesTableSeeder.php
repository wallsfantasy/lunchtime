<?php

use Illuminate\Database\Seeder;

class ProposesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proposes')->insert(static::fixtures());
    }

    public static function fixtures()
    {
        return [
            1 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT10H2M')),

                // Jack - Duck Noodle
                'user_id' => 1,
                'restaurant_id' => 5,
            ],
            2 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT11H24M')),

                // Davy - Goethe
                'user_id' => 2,
                'restaurant_id' => 1,
            ],
            3 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT10H15M')),

                // Will - Food Court
                'user_id' => 3,
                'restaurant_id' => 2,
            ],
            4 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT9H53M')),

                // Elizabeth - Food Court
                'user_id' => 5,
                'restaurant_id' => 2,
            ],
            5 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT11H33M')),

                // Tia - Sai Sa-ard
                'user_id' => 6,
                'restaurant_id' => 5,
            ],
            6 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT11H38M')),

                // James - Sam & Lek
                'user_id' => 7,
                'restaurant_id' => 5,
            ],
            7 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT11H42M')),

                // Babossa - Tree House
                'user_id' => 4,
                'restaurant_id' => 9,
            ],
            8 => [
                'for_date' => (new \DateTime('today'))->add(new \DateInterval('PT11H42M')),

                // Joshamee - Duck Noodle
                'user_id' => 8,
                'restaurant_id' => 5,
            ],
        ];
    }
}
