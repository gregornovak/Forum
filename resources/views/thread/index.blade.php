@extends('layouts.app')

@section('title', 'List of threads')


@section('content')
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
            <hr>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            <hr>
        @endif
        <div class="thread-heading">
            <h3>List of threads</h3>
            <button type="button" class="btn btn-success @guest disabled @endguest" @guest disabled @endguest data-toggle="modal" data-target="#create-thread-modal">
                @guest
                    <span data-toggle="tooltip" data-placement="top" title="You need to login first!">Create a thread</span>
                @else
                    Create a thread
                @endguest
            </button>
        </div>
        @if($threads->count())
            <div class="list-group thread-list">
                @foreach($threads as $thread)
                    <a href="/thread/{{ $thread->id }}" class="list-group-item">
                        <span class="badge" data-toggle="tooltip" data-placement="top" title="Number of posts">{{ $thread->posts_count }}</span>
                        <strong>{{ $thread->title }}</strong>
                        Posted: {{ $thread->created_at }} 
                    </a>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info" role="alert">
                No threads added :(
            </div>
        @endif
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