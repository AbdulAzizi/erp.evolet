<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:oldNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old notifications monthly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();

        foreach($users as $user)
        {
            foreach($user->notifications as $notification)
            {
                if($notification->created_at <= Carbon::now()->subDays(30)){
                    $notification->delete();
                }
            }
        }

     }
}
