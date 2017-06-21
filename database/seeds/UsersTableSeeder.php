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
            ],
            [
                'name' => 'Davy Jones',
                'email' => 'davy@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Will Turner',
                'email' => 'will@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Hector Barbossa',
                'email' => 'hector@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Elizabeth Swan',
                'email' => 'elizabeth@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Tia Dalma',
                'email' => 'tia@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'James Norrington',
                'email' => 'james@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Joshamee Gibbs',
                'email' => 'joshamee@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Cutler Beckett',
                'email' => 'cutler@gmail.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Edward Teach',
                'email' => 'edward@gmail.com',
                'password' => bcrypt('secret'),
            ]
        ];
    }
}
