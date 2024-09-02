<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function postComment(Request $request, Post $post)
    {
        $comment = New Comment;
        $comment->content = $request->content;
        $comment->user_id = auth()->user()->id;

        $post->comments()->save($comment);

        return back()->withMessage('Komentar terkirim!');
    }
}
