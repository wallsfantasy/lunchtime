<?php

use App\Cycle;
use Illuminate\Database\Eloquent\Collection;
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

        /** @var Collection|Cycle[] $cycles */
        $cycles = Cycle::all()->keyBy('id');

        /*
         * Pirate Captains
         * - Jack, Davy, Will, Hector, Elizabeth
         */
        $cycles[1]->users()->sync([1, 2, 3, 4, 10]);

        /*
         * Royal Navy
         * - James, Cutler
         */
        $cycles[2]->users()->sync([7, 9]);

        /*
         * Love Triangle
         * - Will, Elizabeth, James
         */
        $cycles[3]->users()->sync([3, 5, 7]);

        /*
         * Ex Revenge
         * - Jack, Hector, Davy, Tia
         */
        $cycles[4]->users()->sync([1, 4, 2, 6]);
    }

    public static function fixtures()
    {
        return [
            [
                'id' => 1,
                'name' => 'Pirate Captains',
            ],
            [
                'id' => 2,
                'name' => 'Royal Navy',
            ],
            [
                'id' => 3,
                'name' => 'Love Triangle',
            ],
            [
                'id' => 4,
                'name' => 'Ex Revenge',
            ]
        ];
    }
}
