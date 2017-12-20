<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Show the thread with posts.
     * 
     * @return Illuminate\Http\Request
     */
    public function show($id)
    {
        $thread = Thread::find($id);

        $is_updated = false;
        if($thread->updated_at->gt($thread->created_at)) {
            $is_updated = true;
        }
        
        $posts = $thread->posts()->paginate(10);
        
        return view('thread.thread', compact('thread', 'posts', 'is_updated'));
    }

    /**
     * Update the thread title.
     * 
     * @param id $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|min:3'
        ]);

        $thread = Thread::find($request->id);

        if(!$thread->count()) {
            return response()->json([ 'error' => 'Title could not be updated. Please try again.' ], 422);        
        }

        if($thread->user_id != Auth::user()->id) {
            return response()->json([ 'error' => 'You are not allowed to edit this thread.' ], 422);                    
        }

        $thread = $thread->update([
            'title' => $request->title
        ]);

        if(!$thread) {
            return response()->json([ 'error' => 'Title could not be updated. Please try again.' ], 422);        
        }
        
        return response()->json([ 'success' => 'Title has been successfully updated.' ], 200);
    }

    /**
     * Show all threads with posts count for each thread and paginate for 10 items per page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = Thread::withCount('posts')->orderBy('updated_at', 'desc')->paginate(10);
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
     * Save thread, add the first post and increment users post count.
     * 
     * @return string
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

        $user = User::where('id', $user_id)->update([
            'num_of_posts' => DB::raw('num_of_posts+1')
        ]);

        if(!$thread) {
            return response()->json([ 'error' => 'Thread could not be saved. Please try again.' ], 422);                    
        }

        $request->session()->flash('success', "Thread '$request->title' was added successfully!");
        

        return ['redirect' => route('show_thread', $thread->id)];
    }

    /**
     * Delete thread.
     * 
     * @return string
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $user_id = (int)Auth::user()->id;

        $thread = Thread::find($request->id);

        if(!$thread->count()) {
            return response()->json([ 'error' => 'Thread could not be deleted. Please try again.' ], 422);        
        }

        if($thread->user_id != Auth::user()->id) {
            return response()->json([ 'error' => 'You are not allowed to delete this thread.' ], 422);                    
        }

        $request->session()->flash('success', "Thread '$thread->title' was deleted successfully!");
        
        return ['redirect' => route('index')];        
    }
}
