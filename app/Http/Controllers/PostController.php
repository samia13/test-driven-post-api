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
            'create', 'store'
        ]]) ; 
    }

    public function index()
    {
        return response(Post::all());
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
        return response($post);
    }
}
