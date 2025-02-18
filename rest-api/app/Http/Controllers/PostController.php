<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        return PostDetailResource::collection($posts->loadMissing('writer:id,username'));
    }

    public function show($id){
        $post = Post::with('writer:id,username')->findOrFail($id);
        //return response()->json(['data' => $post]);
        return new PostDetailResource($post);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
        //return response()->json('berhasil akses');
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }
}
