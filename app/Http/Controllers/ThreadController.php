<?php

namespace App\Http\Controllers;

use App\Post;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ThreadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the thread.
     */
    public function show($id)
    {
        $thread = Thread::find($id);
        $posts = $thread->posts;
        // dd($posts);
        return view('thread.thread', compact('thread','posts'));
    }

    /**
     * Show all threads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = Thread::withCount('posts')->get();
        // dd($threads);
        // get the user associated with this post
        return view('thread.index', ['threads' => $threads]);
    }

    /**
     * Create a form for the thread.
     */
    public function create()
    {
        return view('thread.create');
    }

    /**
     * Store thread.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'body' => 'required'
        ]);

        $user_id = (int)Auth::user()->id;

        $thread = Thread::create([
            'title' => $request->title,
            'user_id' => $user_id
        ]);

        $post = Post::create([
            'body' => $request->body,
            'user_id' => $user_id,
            'thread_id' => $thread->id
        ]);

        $request->session()->flash('success', "Thread '$request->title' was added successfully!");
        

        return ['redirect' => route('show_thread', $thread->id)];
        // return redirect("/thread/$thread->id");
    }
}
