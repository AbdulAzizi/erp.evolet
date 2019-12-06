<?php

namespace App\Http\Controllers;

use App\Events\PollOptionChosenEvent;
use App\PollAnswer;
use App\QuestionTask;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function storeAPI(Request $request)
    {
        // return $request;
        $user = auth()->user();
        $questionTaskID = $request['questionTask']['id'];
        $selectedOptionID = $request['selected_option_id'];
        // return $selectedOptionID;
        $answer = PollAnswer::where('user_id', $user->id)
            ->where('question_task_id', $questionTaskID)->first();
        // check if already have answer
        if ($answer === null) {
            // if not make new answer
            $user->questionTask()->attach($questionTaskID, ['option_id' => $selectedOptionID]);
            // get question task for notification
            $questionTask = QuestionTask::with('answers', 'question.options')->find($questionTaskID);
            // notify
            event(new PollOptionChosenEvent($questionTask, $user, $selectedOptionID));
        } else {
            if ($answer->option_id != $selectedOptionID) {
                // update existing one
                $answer->option_id = $selectedOptionID;
                // save changes
                $answer->save();
                // get question task for notification
                $questionTask = QuestionTask::with('answers', 'question.options')->find($questionTaskID);
                // notify
                event(new PollOptionChosenEvent($questionTask, $user, $selectedOptionID));
            }
        }

        return QuestionTask::with('question.options', 'answers')->find($questionTaskID);
    }
}
