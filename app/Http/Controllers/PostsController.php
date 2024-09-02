<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2000',
        ]);;
        $post = new Post;
        $post->description = $request->description;
        $post->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $file->move($destinationPath, $fileName);
            $post->image = $fileName;
        }

        $post->save();
        return back()->with('message', 'Berhasil terkirim');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (Auth::user()->is_admin) {
            $post->delete();
            return response()->json(['message' => 'Photo deleted successfully']);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
}


}
