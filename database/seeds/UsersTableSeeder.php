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
        foreach ($this->fixtures() as $item) {
            DB::table('users')->insert($item);
        }
    }

    private function fixtures()
    {
        return [
            [
                'name' => 'Jack Sparrow',
                'email' => 'jack@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'jack',
            ],
            [
                'name' => 'Davy Jones',
                'email' => 'davy@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'davy',
            ],
            [
                'name' => 'Will Turner',
                'email' => 'will@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'will',
            ],
            [
                'name' => 'Hector Barbossa',
                'email' => 'hector@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'hector',
            ],
            [
                'name' => 'Elizabeth Swan',
                'email' => 'elizabeth@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'elizabeth',
            ],
            [
                'name' => 'Tia Dalma',
                'email' => 'tia@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'tia',
            ],
            [
                'name' => 'James Norrington',
                'email' => 'james@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'james',
            ],
            [
                'name' => 'Joshamee Gibbs',
                'email' => 'joshamee@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'joshamee',
            ],
            [
                'name' => 'Cutler Beckett',
                'email' => 'cutler@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'cutler',
            ],
            [
                'name' => 'Edward Teach',
                'email' => 'edward@gmail.com',
                'password' => bcrypt('secret'),
                'api_token' => 'edward',
            ]
        ];
    }
}
