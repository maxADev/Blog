document.addEventListener("DOMContentLoaded", (event) => {
    let deletePost = document.getElementsByClassName('delete-post');
    let deletePostClose = document.getElementById('delete-post-close');
    if (deletePost && deletePostClose) {
        for (var i = 0; i < deletePost.length; i++) {
            deletePost[i].addEventListener('click', modalDeletePost, false);
        }
    
        deletePostClose.addEventListener('click', modalDeletePostClose, false);
    }

    let commentPost = document.getElementsByClassName('delete-comment');
    let deleteCommentClose = document.getElementById('delete-comment-close');
    if (commentPost && deleteCommentClose) {
        for (var i = 0; i < commentPost.length; i++) {
            commentPost[i].addEventListener('click', modalDeleteComment, false);
        }

        deleteCommentClose.addEventListener('click', modalDeleteCommentClose, false);
    }
});

function modalDeletePost()
{
    let postId = this.dataset.postId;
    document.getElementById("post-deletion-container").style.display = "block";
    document.getElementById("confirm-post-deletion").href = "post-deletion-"+postId;
}

function modalDeletePostClose()
{
    document.getElementById("post-deletion-container").style.display = "none";
    document.getElementById("confirm-post-deletion").href = "";
}

function modalDeleteComment()
{
    let commentId = this.dataset.commentId;
    document.getElementById("comment-deletion-container").style.display = "block";
    document.getElementById("confirm-comment-deletion").href = "comment-deletion-"+commentId;
}

function modalDeleteCommentClose()
{
    document.getElementById("comment-deletion-container").style.display = "none";
    document.getElementById("confirm-comment-deletion").href = "";
}