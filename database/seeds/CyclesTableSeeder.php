<?php

use Illuminate\Database\Seeder;

class CyclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cycles')->insert(static::fixtures());
    }

    public static function fixtures()
    {
        return [
            0 => [
                'name' => 'Pirate Captains',
            ],
            1 => [
                'name' => 'Royal Navy',
            ],
            2 => [
                'name' => 'Love Triangle',
            ],
            3 => [
                'name' => 'Ex Revenge',
            ]
        ];
    }
}
