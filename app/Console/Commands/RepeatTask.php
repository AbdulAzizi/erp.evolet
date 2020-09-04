<?php

namespace App\Console\Commands;

use App\Repitition;
use App\Task;
use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RepeatTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repeat:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repeat tasks';

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
        /* 
        ================================================================
        Periods [ 1 => 'день', 2 => 'неделя', 3 => 'месяц', 4 => 'год']
        Actions [ 1 => 'создать задачу', 2 => 'изменить статус' ]
        Weeks [ 0 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
                3 => 'среда', 4 => 'четверг', 5 => 'пятница', 
                6 => 'суббота' ]
        ================================================================
        */

        // Get all tasks with repeatition
        $repeatitions = Repitition::with('tasks')->get();

        foreach ($repeatitions as $repeatition) {

            // Today
            $today = Carbon::now()->toDateString();
            // Get last task from repeat tasks
            $repeatTask = end($repeatition->tasks);
            // Get selected week days of repeat task
            $selectedWeekDays = json_decode($repeatition->weekdays);
            // If action is create new task 

            if (end($repeatTask)) {
                // dd(end($repeatTask)->created_at->add($repeatition->range, $repeatition->range_period)->toDateString() == Carbon::now()->addDays(1)->toDateString());
                if ($this->checkRange(end($repeatTask)->created_at, $repeatition->range, $repeatition->range_period)) {
                    if ($repeatition->action === 1) {
                        if ($this->checkWeekDay($selectedWeekDays)) {
                            if ($today <= $repeatition->end_date) {
                                // Duplicate task but change dates
                                $task = $this->createTask(end($repeatTask), $repeatition->range, $repeatition->range_period);

                                $repeatition->tasks()->attach($task);
                            } else if ($repeatition->end_date === null) {

                                $task = $this->createTask(end($repeatTask), $repeatition->range, $repeatition->range_period);

                                $repeatition->tasks()->attach($task);
                            }
                        }
                    }
                    if ($repeatition->action === 2) {
                        if ($this->checkWeekDay($selectedWeekDays)) {
                            if ($today <= $repeatition->end_date) {
                               $this->changeTaskStatus(end($repeatTask));
                            } else if ($repeatition->end_date === null) {
                                $this->changeTaskStatus(end($repeatTask));
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkRange($createdAt, $range, $rangePeriod)
    {

        return $createdAt->add($range, $rangePeriod)->toDateString() == Carbon::now()->addDays(1)->toDateString();
    }

    public function checkWeekDay($weekDays)
    {
        $today = Carbon::now()->dayOfWeek;

        return in_array($today, $weekDays);
    }

    public function createTask($repeatTask, $range, $rangePeriod)
    {
        $task = Task::create([
            'description' => $repeatTask->description,
            'status_id' => 1,
            'priority' => $repeatTask->priority,
            'planned_time' => $repeatTask->planned_time,
            'deadline' => Carbon::createFromDate($repeatTask->deadline)->addDays(1),
            'responsible_id' => $repeatTask->responsible_id,
            'from_id' => $repeatTask->from_id,
            'from_type' => $repeatTask->from_type,
            'readed' => 0,
            'responsibility_description_id' => $repeatTask->responsibility_description_id,
            'start_date' => Carbon::createFromTimestamp($repeatTask->start_date / 1000, '+05:00')->add($range, $rangePeriod)->timestamp * 1000,
            'repeated' => 1
        ]);

        $task->tags()->attach($repeatTask->tags);

        $task->watchers()->attach($repeatTask->watchers);

        return $task;
    }

    public function changeTaskStatus($task)
    {
        $task->status_id = 1;
        $task->timeSets()->delete();
        $task->deadline = Carbon::createFromDate($task->deadline)->addDays(1);
        $task->read_at = null;
        $task->readed = 0;
        $task->save();
    }
}
