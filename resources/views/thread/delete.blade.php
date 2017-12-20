<div class="modal fade" id="delete-thread-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Do you really want to delete this thread?</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="thread-id" value="{{ $thread->id }}">
                <button type="button" data-dismiss="modal" class="btn btn-cancel-delete-thread">Cancel</button>
                <button type="button" class="btn btn-delete-thread" id="delete-thread">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    let btn = document.querySelector('#delete-thread');
    let threadId = document.querySelector('#thread-id');
    let modalBody = document.querySelector('.modal-body');
    btn.addEventListener('click', deleteThread);

    function deleteThread(e) {
        e.preventDefault();
        
        if(threadId.value.length) {
            axios.delete('{{ action('ThreadController@delete') }}', { data: {
                id: threadId.value
            }})
            .then(function (response) {
                window.location = response.data.redirect;
            })
            .catch(function (error) {
                console.log(error.response);
                let notification = document.createElement('div');
                notification.classList.add('alert', 'alert-danger');
                notification.textContent = `${error.response.data.error}`;
                modalBody.insertBefore(notification, document.querySelector('.btn-cancel-delete-thread'));
            });
        }
    }
});
</script>