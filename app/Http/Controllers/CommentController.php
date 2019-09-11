<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function storeApi(Request $request)
    {
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'body' => $request->body,
        ]);

        $type = $request->commentable_type;

        $comment->$type()->attach($request->commentable_id);
        $comment->load('user');
        
        return $comment;
    }
}
