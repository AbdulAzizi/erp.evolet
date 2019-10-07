<?php

use App\Chat;
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
        $chats = Chat::all();

        // $comments = factory(Comment::class, 50)->create()->pluck('id')->toArray();

        foreach ($tasks as $task) {
            factory(Comment::class, 10)->create([
                'commentable_id' => $task->id,
                'commentable_type' => "App\Task"
            ]);
            // $task->comments()->attach(array_rand($comments, rand(1,15)));
        }

        foreach ($chats as $chat) {
            factory(Comment::class, 10)->create([
                'commentable_id' => $chat->id,
                'commentable_type' => "App\Chat"
            ]);
            // $chat->comments()->attach(array_rand($comments, rand(1,15)));
        }
    }
}
