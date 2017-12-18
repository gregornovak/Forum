@extends('layouts.app')

@section('title', 'List of threads')

@section('content')

    <div class="thread-heading-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            <div class="thread-heading">
                <h2>List of threads</h3>
                <button type="button" class="btn add-thread-btn @guest disabled @endguest" @guest disabled @endguest data-toggle="modal" data-target="#create-thread-modal">
                    @guest
                        <span data-toggle="tooltip" data-placement="top" title="You need to login first!">Create a thread</span>
                    @else
                        Create a thread
                    @endguest
                </button>
            </div>
        </div>
    </div>
    <div class="threads-list-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            @yield('errors')
            @yield('success')
            
            @if($threads->count())
                <div class="list-group thread-list">
                    @foreach($threads as $thread)
                        <a href="/thread/{{ $thread->id }}" class="list-group-item">
                            <span class="badge @if($thread->posts_count > 5){{ 'hot-topic'}}@elseif($thread->posts_count > 0 && $thread->posts_count < 5){{ 'heating-up' }}@endif" data-toggle="tooltip" data-placement="top" title="Number of posts">{{ $thread->posts_count }}</span>
                            <strong>{{ $thread->title }}</strong>
                            <br>
                            <small>Posted by: <strong>{{ $thread->user()['nickname'] }}</strong>, {{ $thread->created_at->diffForHumans() }}</small>
                        </a>
                    @endforeach
                </ul>

                {{ $threads->links() }}
            @else
                <div class="alert alert-info" role="alert">
                    No threads added :(
                </div>
            @endif
        </div>
    </div>

    @if(Auth::user())
        @include('thread.create')
    @endif

    <script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
@endsection