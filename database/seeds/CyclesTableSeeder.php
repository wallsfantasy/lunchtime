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
        foreach (static::fixtures() as $item) {
            DB::table('cycles')->insert($item);
        }
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
