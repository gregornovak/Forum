@extends('layouts.app')

@section('title', "$user->nickname posts")

@section('content')

    <div class="thread-heading-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            <div class="thread-heading">
                <h2>List of your posts</h2>
                <span class="num-of-posts">You have {{ $posts->count() }} posts.</span>
            </div>
        </div>
    </div>
    <div class="threads-list-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
            
            @if($posts->count())
                <div class="list-group thread-list">
                    @foreach($posts as $post)
                        <a href="/thread/{{ $post->thread()['id'] }}" class="list-group-item">
                            <div class="thread-main">
                                <div class="thread-title"><strong class="thread-list-title">{{ $post->thread()['title'] }}</strong></div>
                            </div>
                            <div class="post-body">{{ $post->body }}</div>                            
                            <div class="thread-info">
                                <small class="last-message">Posted: <i>{{ $post->updated_at->diffForHumans() }}</i></small>
                            </div>
                        </a>
                    @endforeach
                </ul>

                {{ $posts->links() }}
            @else
                <div class="alert alert-info" role="alert">
                    No posts added :(
                </div>
            @endif
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', closeNotification);
        function closeNotification(e) {
            if(e.target.classList.contains('flash-close')) {
                e.target.parentElement.parentElement.remove();
            }
        }
    });
    </script>
@endsection