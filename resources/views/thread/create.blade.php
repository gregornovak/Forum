{{--  @extends('layouts.app')

@section('title', 'Create thread')

@section('create_thread')  --}}
<div class="modal fade" id="create-thread-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a new thread</h4>
            </div>
            <div class="modal-body">
                <form id="create-thread" method="post" action="{{ action('ThreadController@store') }}">
                    {{ csrf_field() }}
                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title" class="control-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter thread title...">
                        @if ($errors->has('title')) 
                            <p class="help-block">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                {{ $errors->first('title') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group @if($errors->has('body')) has-error @endif">
                        <label for="body" class="control-label">Post message</label>
                        <textarea class="form-control" name="body" id="body" rows="10" placeholder="Enter your message.."></textarea>
                        @if ($errors->has('body')) 
                            <p class="help-block">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                {{ $errors->first('body') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                {{--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  --}}
                <button id="submit-thread" type="button" class="btn add-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
window.onload = function(){

    let btn = document.querySelector('#submit-thread');
    let form = document.querySelector('#create-thread');
    let modalBody = document.querySelector('.modal-body');
    btn.addEventListener('click', addThread);


    function addThread(e) {
        e.preventDefault();
        form.title.value.trim();
        form.body.value.trim();

        if(form.title.value.length >= 3 && form.body.value.length > 0) {
            axios.post('{{ action('ThreadController@store') }}', {
                title: form.title.value,
                body: form.body.value
            })
            .then(function (response) {
                window.location = response.data.redirect;
            })
            .catch(function (error) {
                let notification = document.createElement('div');
                notification.classList.add('alert', 'alert-danger');
                notification.innerHTML = `${error.response.data.message}`;
                modalBody.insertBefore(notification, form);
            });

            return;

        } else if(form.title.value.length == 0 && form.body.value.length > 0) {
            let parent = form.title.parentElement;
            parent.classList.add('has-error');
            let notification = document.createElement('p');
            notification.classList.add('help-block');
                        
            let span = document.createElement('span');
            span.classList.add('glyphicon', 'glyphicon-exclamation-sign');
            
            parent.appendChild(notification);
            notification.appendChild(span);
            let text = document.createTextNode('Title must be atleast 3 characters long.');
            notification.appendChild(text);
            
            return;
        } else if(form.title.value.length >= 3 && form.body.value.length == 0) {
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
            
            return;
        }

        let titleParent = form.title.parentElement;
        titleParent.classList.add('has-error');
        let notification = document.createElement('p');
        notification.classList.add('help-block');
                    
        let span = document.createElement('span');
        span.classList.add('glyphicon', 'glyphicon-exclamation-sign');
        
        titleParent.appendChild(notification);
        notification.appendChild(span);
        let text = document.createTextNode('Title must be atleast 3 characters long.');
        notification.appendChild(text);
        
        let bodyParent = form.body.parentElement;
        bodyParent.classList.add('has-error');
        notification = document.createElement('p');
        notification.classList.add('help-block');
                    
        span = document.createElement('span');
        span.classList.add('glyphicon', 'glyphicon-exclamation-sign');
        
        bodyParent.appendChild(notification);
        notification.appendChild(span);
        text = document.createTextNode('Post message is a required field.');
        notification.appendChild(text);
        
        return;
    }

    let close = document.querySelector('#close-modal');
    close.addEventListener('click', clearErrors);

    function clearErrors() {
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

        form.title.value = '';
        form.body.value = '';
    }
};
</script>
{{--  @endsection  --}}