<?php

namespace App\Http\Controllers;

use App\PollAnswer;
use App\Question;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function storeAPI(Request $request)
    {
        $user = auth()->user();
        $pollId = $request['poll']['id'];
        $selected_option = $request['selected_option']['id'];

        $poll = PollAnswer::where('user_id', $user->id)
                        ->where('question_id', $pollId)->first();

        if ($poll === null)
            $user->polls()->attach($pollId, ['option_id' => $selected_option]);
        else{
            $poll->option_id = $selected_option;
            $poll->save();
        }
        
        $poll = Question::with('options.users')->find($pollId);
        return $poll;
    }
}
