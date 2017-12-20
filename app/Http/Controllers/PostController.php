<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Thread;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{

    /**
     * Show the thread with posts.
     * 
     * @return Illuminate\Http\Request
     */
    public function show($id)
    {
        $thread = Thread::find($id);
        $posts = $thread->posts;
        return view('thread.thread', compact('thread','posts'));
    }

    /**
     * Show the form for thread editing.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'post_body' => 'required'
        ]);

        $post = Post::find($request->id);

        if(!$post->count()) {
            return response()->json([ 'error' => 'Post could not be updated. Please try again.' ], 422);        
        }

        if($post->user_id != Auth::user()->id) {
            return response()->json([ 'error' => 'You are not allowed to edit this post.' ], 422);                    
        }

        $post = $post->update([
            'body' => $request->post_body
        ]);

        if(!$post) {
            return response()->json([ 'error' => 'Post could not be updated. Please try again.' ], 422);        
        }
        
        return response()->json([ 'success' => 'Post message has been successfully updated.' ], 200);
    }

    /**
     * Store a post.
     * 
     * @return string
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required',
            'thread_id' => 'required'
        ]);

        $user_id = (int)Auth::user()->id;

        $post = Post::create([
            'body' => $request->body,
            'user_id' => $user_id,
            'thread_id' => $request->thread_id
        ]);

        $user = User::where('id', $user_id)->update([
            'num_of_posts' => DB::raw('num_of_posts+1')
        ]);

        $thread = Thread::find($request->thread_id)->touch();
        
        if(!$post) {
            return response()->json([ 'error' => 'Post could not be saved. Please try again.' ], 422);                    
        }

        return ['success' => 'Your message has been added successfully!', 'body' => $request->body, 'user' => Auth::user()->nickname, 'created_at' => $post->created_at->diffForHumans()];
        // return redirect("/thread/$thread->id");
    }

    /**
     * Get all posts by user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function userPosts($user, Request $request)
    {
        if($user != Auth::user()->id) {
            $request->session()->flash('error', 'This action is not allowed.');
            return redirect('/');
        }

        $user = User::find($user);
        $posts = $user->posts()->orderBy('updated_at', 'desc')->paginate(10);

        return view('user.index', compact('user', 'posts'));
    }
}
