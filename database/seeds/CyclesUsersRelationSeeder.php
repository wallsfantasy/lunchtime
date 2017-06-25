<?php

use App\Cycle;
use Illuminate\Database\Seeder;

use App\User;

class CyclesUsersRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = [];
        foreach (UsersTableSeeder::fixtures() as $userFixture) {
            $userIds[] = User::where(['name' => $userFixture['name']])->firstOrFail()->id;
        }

        $cycles = [];
        foreach (CyclesTableSeeder::fixtures() as $cycleFixture) {
            /** @var Cycle[] $cycles */
            $cycles[] = Cycle::where(['name' => $cycleFixture['name']])->firstOrFail();
        }

        // Pirate Captains
        $cycles[0]->users()->sync([$userIds[0], $userIds[1], $userIds[2], $userIds[3], $userIds[4], $userIds[9]]);

        // Royal Navy
        $cycles[1]->users()->sync([$userIds[6], $userIds[8]]);

        // Love Triangle
        $cycles[2]->users()->sync([$userIds[2], $userIds[4], $userIds[6]]);

        // Ex Revenge
        $cycles[3]->users()->sync([$userIds[0], $userIds[3], $userIds[1], $userIds[5]]);
    }
}
