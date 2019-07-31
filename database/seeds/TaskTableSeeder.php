<?php

use Illuminate\Database\Seeder;
use App\User;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            factory(App\Task::class, 10)->create([
                'responsible_id' => $user->id,
            ]);
        }

    }
}
