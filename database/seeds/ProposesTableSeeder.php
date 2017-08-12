<?php

use Carbon\Carbon;
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
                'for_date' => Carbon::today()->add(new \DateInterval('PT10H2M')),
                'proposed_at' => Carbon::now(),

                // Jack - Duck Noodle
                'user_id' => 1,
                'restaurant_id' => 5,
            ],
            2 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT11H24M')),
                'proposed_at' => Carbon::now(),

                // Davy - Goethe
                'user_id' => 2,
                'restaurant_id' => 1,
            ],
            3 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT10H15M')),
                'proposed_at' => Carbon::now(),

                // Will - Food Court
                'user_id' => 3,
                'restaurant_id' => 2,
            ],
            4 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT9H53M')),
                'proposed_at' => Carbon::now(),

                // Elizabeth - Food Court
                'user_id' => 5,
                'restaurant_id' => 2,
            ],
            5 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT11H33M')),
                'proposed_at' => Carbon::now(),

                // Tia - Sai Sa-ard
                'user_id' => 6,
                'restaurant_id' => 5,
            ],
            6 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT11H38M')),
                'proposed_at' => Carbon::now(),

                // James - Sam & Lek
                'user_id' => 7,
                'restaurant_id' => 5,
            ],
            7 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT11H42M')),
                'proposed_at' => Carbon::now(),

                // Babossa - Tree House
                'user_id' => 4,
                'restaurant_id' => 9,
            ],
            8 => [
                'for_date' => Carbon::today()->add(new \DateInterval('PT11H42M')),
                'proposed_at' => Carbon::now(),

                // Joshamee - Duck Noodle
                'user_id' => 8,
                'restaurant_id' => 5,
            ],
        ];
    }
}
