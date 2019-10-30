<?php

namespace App\Http\Controllers;

use App\Events\PollOptionChosenEvent;
use App\PollAnswer;
use App\Question;
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

        if ($answer === null)
            $user->questionTask()->attach($questionTaskID, ['option_id' => $selectedOptionID]);
        else{
            $answer->option_id = $selectedOptionID;
            $answer->save();
        }
        
        $questionTask = QuestionTask::with('answers','question.options')->find($questionTaskID);
        event(new PollOptionChosenEvent($questionTask));
        
        return QuestionTask::with('question.options','answers')->find($questionTaskID);
        // $poll = Question::with('options.users')->find($request['questionTask']['question']['id']);
        // return $poll;
    }
}
