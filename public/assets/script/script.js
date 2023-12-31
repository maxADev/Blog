document.addEventListener("DOMContentLoaded", () => {
    let commentPost = document.getElementsByClassName("delete-comment");
    let deleteCommentClose = document.getElementById("delete-comment-close");
    if (commentPost && deleteCommentClose) {
        for (var a = 0; a < commentPost.length; a++) {
            commentPost[a].addEventListener("click", modalDeleteComment, false);
        }
        deleteCommentClose.addEventListener("click", modalDeleteCommentClose, false);
    }

    let adminDeletePost = document.getElementsByClassName("admin-delete-post");
    let adminDeletePostClose = document.getElementById("admin-delete-post-close");
    if (adminDeletePost && adminDeletePostClose) {
        for (var b = 0; b < adminDeletePost.length; b++) {
            adminDeletePost[b].addEventListener("click", adminModalDeletePost, false);
        }
        adminDeletePostClose.addEventListener("click", adminModalDeletePostClose, false);
    }

    let adminCommentPost = document.getElementsByClassName("admin-delete-comment");
    let adminDeleteCommentClose = document.getElementById("admin-delete-comment-close");
    if (adminCommentPost && adminDeleteCommentClose) {
        for (var c = 0; c < adminCommentPost.length; c++) {
            adminCommentPost[c].addEventListener("click", adminModalDeleteComment, false);
        }
        adminDeleteCommentClose.addEventListener("click", adminModalDeleteCommentClose, false);
    }

    let menuButton = document.getElementById("menu-button");
    let menuDisplay = 1;
    let checkMenu = 1;
    menuButton.addEventListener("click", function() {
        if(menuDisplay === 1) {
            if(checkMenu === 1) {
                menuDisplay = 2;
                checkMenu = 2;
                setTimeout(() => {
                    document.getElementById("menu-container").classList.add("menu-container-display");
                    checkMenu = 1;
                }, "50");
     
                document.getElementById("menu-container").classList.remove("menu-hide");
            }
        } else {
                if(checkMenu === 1) {
                    menuDisplay = 1;
                    checkMenu = 2;
                    document.getElementById("menu-container").classList.remove("menu-container-display");
                    setTimeout(() => {
                        checkMenu = 1;
                        document.getElementById("menu-container").classList.add("menu-hide");
                    }, "500");
                }
            }
    });
});

/**
 * @func modalDeleteComment
 */
function modalDeleteComment() {
    let commentId = this.dataset.commentId;
    document.getElementById("comment-deletion-container").style.display = "block";
    document.getElementById("confirm-comment-deletion").href = "/comment/deletion/"+commentId;
}

/**
 * @func modalDeleteCommentClose
 */
function modalDeleteCommentClose() {
    document.getElementById("comment-deletion-container").style.display = "none";
    document.getElementById("confirm-comment-deletion").href = "";
}

/**
 * @func adminModalDeletePost
 */
function adminModalDeletePost() {
    let postId = this.dataset.postId;
    document.getElementById("admin-post-deletion-container").style.display = "block";
    document.getElementById("admin-confirm-post-deletion").href = "/admin/post/deletion/"+postId;
}

/**
 * @func adminModalDeletePostClose
 */
function adminModalDeletePostClose() {
    document.getElementById("admin-post-deletion-container").style.display = "none";
    document.getElementById("admin-confirm-post-deletion").href = "";
}

/**
 * @func adminModalDeleteComment
 */
function adminModalDeleteComment() {
    let commentId = this.dataset.commentId;
    document.getElementById("admin-comment-deletion-container").style.display = "block";
    document.getElementById("admin-confirm-comment-deletion").href = "/admin/comment/deletion/"+commentId;
}

/**
 * @func adminModalDeleteCommentClose
 */
function adminModalDeleteCommentClose() {
    document.getElementById("admin-comment-deletion-container").style.display = "none";
    document.getElementById("admin-confirm-comment-deletion").href = "";
}
