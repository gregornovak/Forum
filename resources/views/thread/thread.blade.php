@extends('layouts.app')

@section('title', $thread->title)

@section('content')
    <div class="thread-heading-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12 thread-heading">
            <a href="{{ route('index') }}" class="glyphicon glyphicon-chevron-left"></a>
            <h2>{{ $thread->title }}</h2>
        </div>
    </div>
    <div class="thread-posts-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            @yield('errors')
            @yield('success')

            @if($posts->count())
                <ul class="list-posts">
                    @foreach($posts as $post)
                        <li class="list-post-item">
                            <div class="post-info">
                                <strong class="nickname">{{ $post->user()['nickname'] }}</strong>, <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="post-body">
                                {{ $post->body }}                        
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">No posts yet</div>
            @endif
        </div>
    </div>
    <div class="thread-comment-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            @guest
                <div class="login-notice">Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to leave a message.</div>
            @else
                <div class="add-post-container">
                    <div class="form-group">
                        <label class="control-label" for="body">Your response</label>
                        <textarea class="form-control" name="body" id="body" placeholder="Enter your message ..." rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn add-post-btn">Save</button>                    
                    </div>
                </div>
            @endguest
        </div>
    </div>
@endsection