@extends('layouts.app')

@section('title', 'List of threads')

@section('content')

    <div class="thread-heading-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            <div class="thread-heading">
                <h2>List of threads</h3>
                <button type="button" class="btn add-btn @guest disabled @endguest" @guest disabled @endguest data-toggle="modal" data-target="#create-thread-modal">
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
            
            @if($threads->count())
                <div class="list-group thread-list">
                    @foreach($threads as $thread)
                        <a href="/thread/{{ $thread->id }}" class="list-group-item">
                            <div class="thread-main">
                                <div class="thread-title">
                                    <strong class="thread-list-title">{{ $thread->title }}</strong>                            
                                </div>
                                <div class="posts-counter">
                                    @php
                                    $className = '';
                                    if($thread->posts_count >= 10) {
                                        $className = 'hot-topic';
                                    } elseif($thread->posts_count >= 5) {
                                        $className = 'heating-up';
                                    } elseif($thread->posts_count >= 1) {
                                        $className = 'cold-topic';
                                    }
                                    @endphp
                                    <span class="glyphicon glyphicon-comment {{ $className }}" data-toggle="tooltip" data-placement="top" title="Number of posts"></span>
                                    <span class="count {{ $className }}">{{ $thread->posts_count }}</span>
                                </div>
                            </div>
                            <div class="thread-info">
                                <small>Posted by: <strong>{{ $thread->user()['nickname'] }}</strong>, {{ $thread->created_at->diffForHumans() }}</small>                            
                            </div>
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