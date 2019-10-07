<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function storeApi(Request $request)
    {
        // return $request;
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'body' => $request->body,
            'commentable_id' => $request->commentable_id,
            'commentable_type' => $request->commentable_type
        ]);
        
        $comment->load('sender');

        event(new NewComment($comment->commentable_id, $comment->commentable_type, $comment));
        
        return $comment;
    }
}
