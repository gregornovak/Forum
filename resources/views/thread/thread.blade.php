@extends('layouts.app')

@section('title', $thread->title)

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

        <h2>{{ $thread->title }}</h2>

        @if($posts->count())
            <ul class="list-group">
                @foreach($posts as $post)
                    <li class="list-group-item">{{ $post->body }}</li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info">No posts yet</div>
        @endif

    </div>
@endsection