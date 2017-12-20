<div class="modal fade" id="edit-thread-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit title</h4>
            </div>
            <div class="modal-body">
                <form id="edit-thread-form" method="post" action="{{ action('ThreadController@update') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $thread->id }}">
                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title" class="control-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter thread title..." value="{{ $thread->title }}">
                        @if ($errors->has('title')) 
                            <p class="help-block">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                {{ $errors->first('title') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="edit-thread" type="button" class="btn add-btn">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    let btn = document.querySelector('#edit-thread');
    let form = document.querySelector('#edit-thread-form');
    let modalBody = document.querySelector('.modal-body');
    let oldTitle = document.querySelector('.thread-heading h2');
    btn.addEventListener('click', editThread);


    function editThread(e) {
        e.preventDefault();
        form.title.value.trim();

        if(form.title.value.length >= 0) {
            axios.put('{{ action('ThreadController@update') }}', {
                id: form.id.value,
                title: form.title.value
            })
            .then(function (response) {
                // hide the modal
                $('#edit-thread-modal').modal('hide');
                // update the title
                oldTitle.textContent = form.title.value;
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
            })
            .catch(function (error) {
                let notification = document.createElement('div');
                notification.classList.add('alert', 'alert-danger');
                notification.textContent = `${error.response.data.error}`;
                modalBody.insertBefore(notification, form);
            });

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
    }
});
</script>