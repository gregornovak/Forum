@extends('thread.index')

@section('errors')

@if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    <hr>
@endif

@endsection