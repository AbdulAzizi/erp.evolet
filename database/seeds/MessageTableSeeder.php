<?php

use App\Chat;
use App\Message;
use App\Task;
use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
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

        // $messages = factory(Message::class, 50)->create()->pluck('id')->toArray();

        foreach ($tasks as $task) {
            factory(Message::class, 10)->create([
                'messageable_id' => $task->id,
                'messageable_type' => "App\Task"
            ]);
            // $task->messages()->attach(array_rand($messages, rand(1,15)));
        }

        foreach ($chats as $chat) {
            factory(Message::class, 10)->create([
                'messageable_id' => $chat->id,
                'messageable_type' => "App\Chat"
            ]);
            // $chat->messages()->attach(array_rand($messages, rand(1,15)));
        }
    }
}
