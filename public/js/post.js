// jQuery(function($){
//
//     $('#comment-form').on('submit', function (event) {
//         event.preventDefault();
//
//         const url = new URL(window.location.href);
//
//         $.ajax({
//             'url': `${url.pathname}/add-comment`,
//             'method': 'post',
//             'data': new FormData(event.currentTarget),
//             'processData': false,
//             'contentType': false,
//             'success': html => {
//                 $('.comments-list').prepend(html);
//                 $('#add-comment-success-modal').modal('show');
//                 $('#comment-form').trigger('reset');
//              }
//         });
//     });
// });


document.addEventListener('DOMContentLoaded', function (){

    const form = document.getElementById('comment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const url = new URL(window.location.href);

        fetch(`${url.pathname}/add-comment`, {
            method: 'post',
            body: new FormData(event.currentTarget),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                const container = document.querySelector('.comments-list');
                container.innerHTML = html + container.innerHTML;

                document.getElementById('comment-form').reset();

                $('#add-comment-success-modal').modal('show');
            });
    });
});