<?php

use Illuminate\Database\Seeder;
use App\Chat;
class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();
        $userIds = $users->pluck('id')->toArray();

        foreach ($users as $user) {
            $chats = factory(Chat::class, 2)->create([
                'admin_id' => $user->id
            ])->each( function ($chat) use ($userIds) {
                $chat->participants()->attach(array_rand($userIds, rand(1,10)));
            });

            
        }
    }
}
