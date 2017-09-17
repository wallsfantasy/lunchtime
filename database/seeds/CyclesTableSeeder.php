<?php

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Projection\CycleProjector;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Predis\Pipeline\Pipeline;

class CyclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fixtures = static::fixtures();
        DB::table('cycles')->insert($fixtures);

        // cycle members
        /** @var Collection|Cycle[] $cycles */
        $cycles = Cycle::all()->sortBy('id');

        /*
         * Pirate Captains
         * - Jack, Davy, Will, Hector, Elizabeth
         */
        $cycles[0]->members()->createMany(
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
        $cycles[1]->members()->createMany(
            [
                ['user_id' => 7],
                ['user_id' => 9],
            ]
        );

        /*
         * Love Triangle
         * - Will, Elizabeth, James
         */
        $cycles[2]->members()->createMany(
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
        $cycles[3]->members()->createMany(
            [
                ['user_id' => 1],
                ['user_id' => 4],
                ['user_id' => 2],
                ['user_id' => 6],
            ]
        );

        Redis::pipeline(function (Pipeline $redis) use ($cycles) {
            foreach ($cycles as $cycle) {
                // cycle projection
                $redis->hmset(
                    CycleProjector::KEY_PREFIX . $cycle['id'],
                    [
                        'id' => $cycle['id'],
                        'name' => $cycle['name'],
                        'lunchtime' => $cycle['lunchtime'],
                        'propose_until' => $cycle['propose_until'],
                    ]
                );

                // cycle ids
                $redis->sadd(CycleProjector::KEY_IDS, [$cycle['id']]);
                $redis->sadd(CycleProjector::KEY_PREFIX_MEMBERS . $cycles[0]->id, [1, 2, 3, 4, 10]);
                $redis->sadd(CycleProjector::KEY_PREFIX_MEMBERS . $cycles[1]->id, [7, 9]);
                $redis->sadd(CycleProjector::KEY_PREFIX_MEMBERS . $cycles[2]->id, [3, 5, 7]);
                $redis->sadd(CycleProjector::KEY_PREFIX_MEMBERS . $cycles[3]->id, [1, 4, 2, 6]);
            }
        });
    }

    public static function fixtures()
    {
        return [
            1 => [
                'id' => '00000000-0000-0000-0000-000000000001',
                'name' => 'Pirate Captains',
                'lunchtime' => '13:00:00',
                'propose_until' => '13:00:00',
            ],
            2 => [
                'id' => '00000000-0000-0000-0000-000000000002',
                'name' => 'Royal Navy',
                'lunchtime' => '10:00:00',
                'propose_until' => '10:00:00',
            ],
            3 => [
                'id' => '00000000-0000-0000-0000-000000000003',
                'name' => 'Love Triangle',
                'lunchtime' => '12:00:00',
                'propose_until' => '12:00:00',
            ],
            4 => [
                'id' => '00000000-0000-0000-0000-000000000004',
                'name' => 'Ex Problem',
                'lunchtime' => '11:00:00',
                'propose_until' => '11:00:00',
            ],
        ];
    }
}
