<?php

use App\Model\Cycle\Cycle;
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
        $cycles[1]->members()->createMany(
            [
                ['user_id' => 1],
                ['user_id' => 2],
                ['user_id' => 3],
                ['user_id' => 4],
                ['user_id' => 10],
            ]
        );

        /*
         * Royal Navy
         * - James, Cutler
         */
        $cycles[2]->members()->createMany(
            [
                ['user_id' => 7],
                ['user_id' => 9],
            ]
        );

        /*
         * Love Triangle
         * - Will, Elizabeth, James
         */
        $cycles[3]->members()->createMany(
            [
                ['user_id' => 3],
                ['user_id' => 5],
                ['user_id' => 7],
            ]
        );

        /*
         * Ex Revenge
         * - Jack, Hector, Davy, Tia
         */
        $cycles[4]->members()->createMany(
            [
                ['user_id' => 1],
                ['user_id' => 4],
                ['user_id' => 2],
                ['user_id' => 6],
            ]
        );
    }

    public static function fixtures()
    {
        return [
            1 => [
                'name' => 'Pirate Captains',
                'lunchtime' => '13:00:00',
                'propose_until' => '13:00:00',
            ],
            2 => [
                'name' => 'Royal Navy',
                'lunchtime' => '10:00:00',
                'propose_until' => '10:00:00',
            ],
            3 => [
                'name' => 'Love Triangle',
                'lunchtime' => '12:00:00',
                'propose_until' => '12:00:00',
            ],
            4 => [
                'name' => 'Ex Problem',
                'lunchtime' => '11:00:00',
                'propose_until' => '11:00:00',
            ],
        ];
    }
}
