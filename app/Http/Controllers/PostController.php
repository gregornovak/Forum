<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;

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
     * @param id $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Show all threads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = Thread::withCount('posts')->paginate(10);
        return view('thread.index', ['threads' => $threads]);
    }

    /**
     * Create a form for the thread.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('thread.create');
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
        
        if(!$post) {
            return response()->json([ 'error' => 'Post could not be saved. Please try again.' ], 422);                    
        }

        return ['success' => 'Your message has been added successfully!', 'body' => $request->body, 'user' => Auth::user()->nickname, 'created_at' => $post->created_at->diffForHumans()];
        // return redirect("/thread/$thread->id");
    }
}
