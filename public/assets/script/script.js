$(document).ready(function() {
    $(document).on('click', '.delete-post', function() {
        let postId = $(this).data('post-id');
        $('.post-deletion-container').css('display', 'block')
        $('.confirm-post-deletion').attr('href', 'post-deletion-'+postId+'');
    })

    $(document).on('click', '.delete-post-close', function() {
        $('.post-deletion-container').css('display', 'none')
        $('.confirm-post-deletion').attr('href', '');
    })

    $(document).on('click', '.delete-comment', function() {
        let commentId = $(this).data('comment-id');
        $('.comment-deletion-container').css('display', 'block')
        $('.confirm-comment-deletion').attr('href', 'comment-deletion-'+commentId+'');
    })

    $(document).on('click', '.delete-comment-close', function() {
        $('.comment-deletion-container').css('display', 'none')
        $('.confirm-comment-deletion').attr('href', '');
    })
});