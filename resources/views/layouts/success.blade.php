@extends('thread.index')

@section('errors')

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    <hr>
@endif

@endsection