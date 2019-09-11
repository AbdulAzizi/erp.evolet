<?php

use App\Comment;
use App\Task;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = Task::all();

        $comments = factory(Comment::class, 8)->create();

        foreach ($tasks as $task) {
            $task->comments()->attach($comments);
        }
    }
}
