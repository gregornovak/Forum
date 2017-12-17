{{--  @extends('layouts.app')

@section('title', 'Create thread')

@section('create_thread')  --}}
<div class="modal fade" id="create-thread-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                        {{--  <input type="text" class="form-control" id="body" name="body" placeholder="Enter your message...">  --}}
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="submit-thread" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
    {{--  <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12">
        <h1>Create a new thread</h1>
        <hr class="break">
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
        
            <button type="button" id="submit-thread" class="btn btn-default">Submit</button>
        </form>
    </div>  --}}
<script>
window.onload = function(){

    let btn = document.querySelector('#submit-thread');
    let form = document.querySelector('#create-thread');
    let title = form.title;

    btn.addEventListener('click', addThread);

    function addThread(e) {
        e.preventDefault();
        title.value.trim();

        if(title.value.length >= 3 && form.body.value.length > 0) {
            axios.post('{{ action('ThreadController@store') }}', {
                title: title.value,
                body: form.body.value
            })
            .then(function (response) {
                window.location = response.data.redirect;
            })
            .catch(function (error) {
                let modalBody = document.querySelector('.modal-body');
                let notification = document.createElement('div');
                notification.classList.add('alert', 'alert-danger');
                notification.innerHTML = `${error.response.data.message}`;
                modalBody.insertBefore(notification, form);
            });

            return;
        }

        let output = `
            <p class="help-block">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                Title must be atleast 5 characters long.
            </p>`;

        $('.form-group').addClass('has-error');
        $('#title').after(output);
    }

    /*let btn = $('#submit-thread');
    let form = $('#create-thread');

    btn.on('click', function(e) {
        e.preventDefault();
        
        let title = $('#title');
        if(title.val().length >= 5) {
            axios.post('{{ action('ThreadController@store') }}', {
                title: title.val()
            })
            .then(function (response) {
                window.location = response.data.redirect;
            })
            .catch(function (error) {

                let errors = `
                    <div class="alert alert-danger">
                        
                    </div>`;


                if(error.response.data.errors.title.length) {

                    let output = `
                        <p class="help-block">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            ${error.response.data.errors.title}
                        </p>`;

                    $('.form-group').addClass('has-error');
                    $('#title').after(output);
                }
                
            });
            //form.submit();
            return;
        }

        let output = `
        <p class="help-block">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            Title must be atleast 5 characters long.
        </p>`;

        $('.form-group').addClass('has-error');
        $('#title').after(output);
        
    });*/
};
</script>
{{--  @endsection  --}}