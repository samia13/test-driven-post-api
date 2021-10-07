<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api', ['only'=>[
            'create', 'store', 'update', 'delete'
        ]]) ; 
    }

    public function index()
    {
        return response(Post::with('tags')->get());
    }

    public function show(Post $post)
    {
        if(!$post){
            return response()->json(["Post not found"], 404);
        }
        return response($post);
    }

    public function store(Request $request)
    {
        $post = Post::create($request->post());
        if($request->has('tags'))
            $this->syncPostTags($request->tags, $post);
        return response($post);
    }

    public function update(Request $request, $id)
    {
        // route model binding won't work for testing because of the withoutMiddleware trait
        // so we're using findOrFail instead

        $post = Post::findOrFail($id);
        $post->update($request->post());
        
        if($request->has('tags'))
            $this->syncPostTags($request->tags, $post);
        
        return response($post->load('tags'));
    }

    function syncPostTags($tags, $post)
    {
        $post->tags()->sync($tags);
    }
}
