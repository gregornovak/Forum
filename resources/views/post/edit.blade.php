<div class="modal fade" id="edit-post-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit post</h4>
            </div>
            <div class="modal-body">
                <form id="edit-post-form" method="post" action="{{ action('PostController@update') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="post-id" value="">
                    <div class="form-group">
                        <label for="post-body" class="control-label">Post message</label>
                        <textarea type="text" class="form-control" id="post-body" name="post_body" rows="3" placeholder="Edit post message..." value=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="edit-post" type="button" class="btn add-btn">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    let btn = document.querySelector('#edit-post');
    let form = document.querySelector('#edit-post-form');
    let modalBody = document.querySelector('.modal-body');
    btn.addEventListener('click', editPost);


    function editPost(e) {
        e.preventDefault();
        form.post_body.value.trim();

        if(form.post_body.value.length > 0) {
            axios.put('{{ action('PostController@update') }}', {
                id: form.id.value,
                post_body: form.post_body.value
            })
            .then(function (response) {
                // hide the modal
                $('#edit-post-modal').modal('hide');
                // update the title
                let post = document.querySelector(`.post-${form.id.value}`);
                post.textContent = form.post_body.value;
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
                notification.textContent = (error.response.data.post_body) ? `${error.response.data.post_body}` : `${error.response.data.error}`;
                modalBody.insertBefore(notification, form);
            });

            return;
        }

        let bodyParent = form.title.parentElement;
        bodyParent.classList.add('has-error');
        let notification = document.createElement('p');
        notification.classList.add('help-block');
                    
        let span = document.createElement('span');
        span.classList.add('glyphicon', 'glyphicon-exclamation-sign');
        
        bodyParent.appendChild(notification);
        notification.appendChild(span);
        let text = document.createTextNode('Post message is a required field.');
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

        form.post_body.value = '';
    }
});
</script>