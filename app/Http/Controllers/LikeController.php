<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class LikeController extends Controller
{
    public function postlike(Request $request)
    {
        $postId = $request->input('postId');
        $post=Post::find($postId);

        if(!$post->YouLiked()){
            $post->YouLikeIt();
            return response()->json(['status'=>'success','message' => 'liked']);
        } else {
            $post->YouUnlike();
            return response()->json(['status'=>'success','message' => 'unliked']);
        }
    }
}
