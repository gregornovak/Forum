@extends('layouts.app')

@section('title', $thread->title)

@section('content')
    <div class="thread-heading-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12 thread-heading">
            <a href="{{ route('index') }}" class="glyphicon glyphicon-chevron-left"></a>
            <h2>{{ $thread->title }}</h2>
            @if($thread->user_id == Auth::user()->id)
                <div class="thread-actions">
                    <span class="glyphicon glyphicon-remove delete-thread-btn" data-toggle="modal" data-target="#delete-thread-modal"></span>
                    <span class="glyphicon glyphicon-pencil edit-thread-btn" data-toggle="modal" data-target="#edit-thread-modal"></span>
                </div>
            @endif
            @if($is_updated)
                <span class="thread-updated">Updated {{ $thread->updated_at->diffForHumans() }}</span>
            @endif
        </div>
    </div>
    <div class="thread-posts-container">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
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
                    <form id="add-new-post" method="post" action="{{ action('PostController@store') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        <div class="form-group">
                            <label class="control-label" for="body">Your response</label>
                            <textarea class="form-control" name="body" id="body" placeholder="Enter your message ..." rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn add-post-btn" id="submit-post">Post</button>                    
                        </div>
                    </form>
                </div>
            @endguest
        </div>
    </div>

    @if(Auth::user())
        @include('thread.edit')
        @include('thread.delete')
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        let btn = document.querySelector('#submit-post');
        let form = document.querySelector('#add-new-post');
        btn.addEventListener('click', addPost);

        function addPost(e) {
            e.preventDefault();
            form.body.value.trim();
            
            if(form.body.value.length > 0) {
                axios.post('{{ action('PostController@store') }}', {
                    body: form.body.value,
                    thread_id: form.thread_id.value
                })
                .then(function (response) {
                    // create necessary elements for the "flash message"
                    let row = document.querySelector('.container-fluid .row');
                    let rootEl = document.createElement('div');
                    rootEl.classList.add('flash-container', 'flash-success-messages');

                    let containerEl = document.createElement('div');
                    containerEl.classList.add('col-lg-6', 'col-lg-offset-3', 'col-md-10', 'col-md-offset-1', 'col-sm-12', 'flash-parent');

                    let textEl = document.createElement('p');
                    textEl.classList.add('success-text');
                    textEl.textContent = response.data.success;

                    let closeBtn = document.createElement('span');
                    closeBtn.classList.add('glyphicon', 'glyphicon-remove', 'success-text-close', 'flash-close');

                    // append elements to the DOM
                    row.insertBefore(rootEl, document.querySelector('.thread-heading-container'));
                    rootEl.appendChild(containerEl);
                    containerEl.appendChild(textEl);
                    containerEl.appendChild(closeBtn);

                    // clear the input field
                    form.body.value = '';

                    // create elements for the last added post
                    let ul = document.querySelector('.list-posts');
                    let li = document.createElement('li');
                    li.classList.add('list-post-item');
                    let post = `
                        <div class="post-info">
                            <strong class="nickname">${response.data.user}</strong>, <small>${response.data.created_at}</small>
                        </div>
                        <div class="post-body">${response.data.body}</div>
                    `;

                    ul.appendChild(li);
                    li.innerHTML = post;
                })
                .catch(function (error) {
                    // create necessary elements for the "flash message"
                    let row = document.querySelector('.container-fluid .row');
                    let rootEl = document.createElement('div');
                    rootEl.classList.add('flash-container', 'flash-error-messages');

                    let containerEl = document.createElement('div');
                    containerEl.classList.add('col-lg-6', 'col-lg-offset-3', 'col-md-10', 'col-md-offset-1', 'col-sm-12', 'flash-parent');

                    let textEl = document.createElement('p');
                    textEl.classList.add('error-text');
                    textEl.textContent = error.response.data.error;

                    let closeBtn = document.createElement('span');
                    closeBtn.classList.add('glyphicon', 'glyphicon-remove', 'error-text-close', 'flash-close');

                    // append elements to the DOM
                    row.insertBefore(rootEl, document.querySelector('.thread-heading-container'));
                    rootEl.appendChild(containerEl);
                    containerEl.appendChild(textEl);
                    containerEl.appendChild(closeBtn);
                });

                return;
            }
            
            let parent = form.body.parentElement;
            parent.classList.add('has-error');
            let notification = document.createElement('p');
            notification.classList.add('help-block');
                        
            let span = document.createElement('span');
            span.classList.add('glyphicon', 'glyphicon-exclamation-sign');
            
            parent.appendChild(notification);
            notification.appendChild(span);
            let text = document.createTextNode('Post message is a required field.');
            notification.appendChild(text);

            btn.classList.add('disabled');
            btn.disabled = true;
        }

        form.body.addEventListener('keyup', clearErrors);

        function clearErrors() {
            btn.disabled = false;
            btn.classList.remove('disabled');

            let errors = document.querySelectorAll('.has-error');
            let helpBlockErrors = document.querySelectorAll('.help-block');

            if(errors.length) {
                errors.forEach((err) => {
                    err.classList.remove('has-error');
                });
            }

            if(helpBlockErrors.length) {
                helpBlockErrors.forEach((err) => {
                    err.remove();
                });
            }
        }

        document.body.addEventListener('click', closeNotification);
        function closeNotification(e) {
            if(e.target.classList.contains('flash-close')) {
                e.target.parentElement.parentElement.remove();
            }
        }

    });
    </script>
@endsection