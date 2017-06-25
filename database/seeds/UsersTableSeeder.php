<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::fixtures() as $item) {
            DB::table('users')->insert($item);
        }
    }

    public static function fixtures()
    {
        return [
            0 => [
                'name' => 'Jack Sparrow',
                'email' => 'jack@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'jack',
            ],
            1 => [
                'name' => 'Davy Jones',
                'email' => 'davy@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'davy',
            ],
            2 => [
                'name' => 'Will Turner',
                'email' => 'will@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'will',
            ],
            3 => [
                'name' => 'Hector Barbossa',
                'email' => 'hector@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'hector',
            ],
            4 => [
                'name' => 'Elizabeth Swan',
                'email' => 'elizabeth@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'elizabeth',
            ],
            5 => [
                'name' => 'Tia Dalma',
                'email' => 'tia@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'tia',
            ],
            6 => [
                'name' => 'James Norrington',
                'email' => 'james@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'james',
            ],
            7 => [
                'name' => 'Joshamee Gibbs',
                'email' => 'joshamee@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'joshamee',
            ],
            8 => [
                'name' => 'Cutler Beckett',
                'email' => 'cutler@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'cutler',
            ],
            9 => [
                'name' => 'Edward Teach',
                'email' => 'edward@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'edward',
            ]
        ];
    }
}
