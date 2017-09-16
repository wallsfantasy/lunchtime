<?php

use App\Model\Cycle\Projection\UserProjector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;
use Predis\Pipeline\Pipeline;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fixtures = self::fixtures();
        DB::table('users')->insert($fixtures);

        Redis::pipeline(function (Pipeline $redis) use ($fixtures) {
            foreach ($fixtures as $id => $user) {
                $redis->hmset(
                    UserProjector::KEY_PREFIX . $id,
                    [
                        'id' => $id,
                        'name' => $user['name'],
                        'email' => $user['email'],
                    ]
                );
            }
        });
    }

    public static function fixtures()
    {
        return [
            1 => [
                'name' => 'Jack Sparrow',
                'email' => 'jack@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'jack',
            ],
            2 => [
                'name' => 'Davy Jones',
                'email' => 'davy@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'davy',
            ],
            3 => [
                'name' => 'Will Turner',
                'email' => 'will@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'will',
            ],
            4 => [
                'name' => 'Hector Barbossa',
                'email' => 'hector@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'hector',
            ],
            5 => [
                'name' => 'Elizabeth Swan',
                'email' => 'elizabeth@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'elizabeth',
            ],
            6 => [
                'name' => 'Tia Dalma',
                'email' => 'tia@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'tia',
            ],
            7 => [
                'name' => 'James Norrington',
                'email' => 'james@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'james',
            ],
            8 => [
                'name' => 'Joshamee Gibbs',
                'email' => 'joshamee@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'joshamee',
            ],
            9 => [
                'name' => 'Cutler Beckett',
                'email' => 'cutler@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'cutler',
            ],
            10 => [
                'name' => 'Edward Teach',
                'email' => 'edward@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'edward',
            ],
        ];
    }
}
