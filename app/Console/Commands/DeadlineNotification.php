<?php

namespace App\Console\Commands;

use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\TaskDeadlineIsNear;

class DeadlineNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for notifying users of 2, 1 days left deadline of task';

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
        $tasks = Task::where('status_id', '!=', 4)->whereDate('deadline', '=', Carbon::now()->addDays(2))
                        ->orWhereDate('deadline', '=', Carbon::now()->addDays(3))
                        ->orWhereDate('deadline', '=', Carbon::now()->addDays(1))
                        ->orWhereDate('deadline', '=', Carbon::now())
                        ->get();

        foreach ($tasks as $task) {

            $task->responsible->notify(new TaskDeadlineIsNear($task));

        }
        
        echo ('notification sent');

    }
}
