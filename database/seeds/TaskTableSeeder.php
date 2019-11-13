<?php

use App\Tag;
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
        $tags = Tag::all();

        foreach ($users as $user) {
            $tasks = factory(App\Task::class, 30)->create([
                'responsible_id' => $user->id,
            ]);

            foreach ($tasks as $task) {
                $task->tags()->attach($tags->random( rand(1,3) ));
            }
        }

    }
}
