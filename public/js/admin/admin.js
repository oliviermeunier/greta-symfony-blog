
// FONCTIONS
function onClickRemovePost(event)
{
    event.preventDefault();

    const id = event.currentTarget.dataset.id;

    confirmRemoveButton.dataset.id = event.currentTarget.dataset.id;

    $('#remove-post-confirm-modal').modal('show');
}


function onClickConfirmRemovePost(event)
{
    $('#remove-post-confirm-modal').modal('hide');

    const id = event.currentTarget.dataset.id;
    const url = `/admin/post/remove/${id}`;
    const options = {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }

    fetch(url, options)
        .then(response => response.json())
        .then(id => {
            document.getElementById(`post-${id}`).remove();
            $('#remove-post-success-modal').modal('show');
        });
}

// CODE PRINCIPAL
document.querySelectorAll('.post-remove').forEach(removeLink => {
    removeLink.addEventListener('click', onClickRemovePost);
});

const confirmRemoveButton = document.querySelector('#remove-post-confirm-modal .confirm-button');

confirmRemoveButton.addEventListener('click', onClickConfirmRemovePost);