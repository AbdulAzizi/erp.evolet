<?php

namespace App\Console\Commands;

use App\Notifications\EventDeadline;
use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\TaskDeadlineIsNear;
use Illuminate\Support\Facades\Auth;

class CalendarEventNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendarevent:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for notifying users of 1 days left deadline in planned calendar';

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
        // ->whereDate('deadline', '=', Carbon::now()->addDays(2))
        //                 ->orWhereDate('deadline', '=', Carbon::now()->addDays(3))
        //                 ->orWhereDate('deadline', '=', Carbon::now()->addDays(1))
        //                 ->orWhereDate('deadline', '=', Carbon::now())
        //                 ->get();

        $tasks = Task::where([
            'status_id' => [1,2,3],
            ])->where('start_date', '!=', null)->get();

        foreach ($tasks as $task) {
            $calculcateEventDeadline = Carbon::createFromTimestampMs(intval($task->start_date));
            $now = Carbon::now('+05:00')->toDateTimeString();
            if($now <= $calculcateEventDeadline->toDateTimeString()) {
                if($calculcateEventDeadline->diffInMinutes($now) === 59) {
                    $task->responsible->notify(new EventDeadline($task, 1));
                } else if($calculcateEventDeadline->diffInMinutes($now) === 29) {
                    $task->responsible->notify(new EventDeadline($task, 30));
                } else if($calculcateEventDeadline->diffInMinutes($now) === 14) {
                    $task->responsible->notify(new EventDeadline($task, 15));
                }
            };
        }
        
        echo('event notification sent');

    }
}
