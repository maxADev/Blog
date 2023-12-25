<?php

namespace src\Controllers;

use src\Entity\PostEntity;
use src\Models\PostModel;
use App\SuperGlobal;
use App\Redirect;
use src\Controllers\CommentController;

// Post Controller.
class PostController
{

    /**
     *
     * @var $postModel for PostModel class
     */
    private $postModel;

    /**
     *
     * @var $superGlobal for SuperGlobal class
     */
    private $superGlobal;

    /**
     *
     * @var $redirect for Redirect class
     */
    private $redirect;

    /**
     *
     * @var $commentController for CommentController class
     */
    private $commentController;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();
        $this->commentController = new CommentController();

    }//end __construct()


    /**
     * Create post page
     *
     * @return view
     */
    public function postCreation()
    {
        $errors;

        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('/login');
        };

        $varValue = ['user' => $this->superGlobal->getCurrentUser()];

        if ($this->superGlobal->postExist() === true) {
            $post = [];
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $postValue['FkUserId'] = $varValue['user']['id'];
                $postValues = new PostEntity($postValue);
                if ($postValues->isValid() === true) {
                if ($this->postModel->createPost($postValues) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été créé']);
                    $this->redirect->getRedirect('/posts');
                }
            } else {
                    $newPostValues = [];
                    if (empty($postValues) === false) {
                        foreach($postValues as $key => $newValue) {
                            if ($key != 'FKUserId' && $key != 'error') {
                                $newKey = lcfirst(str_replace('post', '', $key));
                                $newPostValues[$newKey] = $newValue;
                            }
                        }
                        $varValue['postValue'] = $newPostValues;
                    }
                    $this->superGlobal->createFlashMessage($postValues->getError());
            }
            } else {
                if (empty($postValue) === false) {
                    $varValue['postValue'] = $postValue;
                }
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $varValue['formSetting'] = [
                                   "title"   => ["label" => "Titre", "type" => "text", "placeholder" => "Titre du post"],
                                   "chapo"   => ["label" => "Chapo", "type" => "text", "placeholder" => "Chapo du post"],
                                   "content" => ["label" => "Contenu", "type" => "textarea", "placeholder" => "Contenu du post"]
                                    ];

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'postCreation.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end postCreation()


    /**
     * Get post list
     *
     * @return view
     */
    public function postList()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        $postList = $this->postModel->getPostList();

        if (empty($postList) === false) {
            $varValue['postList'] = $postList;
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'postList.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end postList()


    /**
     * Get post list
     *
     * @return view
     */
    public function readPost()
    {
        $varValue = [];
        $errors;
        $varValue['commentModificationId'] = 0;

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
            if (empty($getValueCommentId) === false) {
                $varValue['commentModificationId'] = $getValueCommentId;
            }
        }

        if (empty($getValuePostId) === false) {
            $post = $this->postModel->getPost($getValuePostId);
            if (empty($post) === false) {
                $errors = [];
                $varValue['post'] = $post;
                $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId);
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = [];
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $postValue['FKUserId'] = $varValue['user']['id'];
                $postValue['FKPostId'] = $getValuePostId;
                if (isset($postValue['comment_content_modification']) === false) {
                    if (empty($varValue['user']['id']) === false) {
                        $commentResult = $this->commentController->createPostComment($postValue);
                        if ($commentResult === true) {
                            $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été posté, il est en attente de validation']);
                            $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId);
                        } else {
                            $this->superGlobal->createFlashMessage($commentResult);    
                        }
                    }
                } else {
                    $postValue['commentContent'] = $postValue['comment_content_modification'];
                    $postValue['commentId'] = $getValueCommentId;
                    $commentValue = $postValue;
                    $commentResult = $this->commentController->commentPostModification($commentValue);
                    if ($commentResult === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été modifié, il est en attente de validation']);
                        $this->redirect->getRedirect('/post/'.$post['id'].'/'.str_replace(' ', '-', $post['title']).'');
                    } else {
                        $this->superGlobal->createFlashMessage($commentResult);
                        $this->redirect->getRedirect('/post/'.$post['id'].'/'.str_replace(' ', '-', $post['title']).'');
                    }
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'readPost.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end readPost()


    /**
     * Get post list
     *
     * @return view
     */
    public function postModification()
    {
        $varValue = [];
        $errors;

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $getValuePostTtile = $this->superGlobal->getGetData('postTitle');
            $postValue = $this->postModel->getPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($varValue['user']['id'] !== $postValue['FK_user_id']) {
                    $this->redirect->getRedirect('/posts');
                }
                $varValue['postValue'] = $postValue;
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $postValue['id'] = $getValuePostId;
                $postValues = new PostEntity($postValue);
                if ($postValues->isValid() === true) {
                if ($this->postModel->postModification($postValue) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été modifié']);
                    $this->redirect->getRedirect('/post/'.$getValuePostId.'/'.$getValuePostTtile.'');
                }
            } else {
                    $newPostValues = [];
                    if (empty($postValues) === false) {
                        foreach($postValues as $key => $newValue) {
                            if ($key != 'FKUserId' && $key != 'error') {
                                $newKey = lcfirst(str_replace('post', '', $key));
                                $newPostValues[$newKey] = $newValue;
                            }
                        }
                        $varValue['postValue'] = $newPostValues;
                    }
                    $this->superGlobal->createFlashMessage($postValues->getError());
            }
            } else {
                if (empty($postValue) === false) {
                    $varValue['postValue'] = $postValue;
                }
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if


        $varValue['formSetting'] = [
                                   "title"   => ["label" => "Titre", "type" => "text", "placeholder" => "Titre du post"],
                                   "chapo"   => ["label" => "Chapo", "type" => "text", "placeholder" => "Chapo du post"],
                                   "content" => ["label" => "Contenu", "type" => "textarea", "placeholder" => "Contenu du post"]
                                   ];

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'postModification.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end postModification()


    /**
     * Post deletion
     *
     * @return void
     */
    public function postDeletion()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $postValue = $this->postModel->getPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($varValue['user']['id'] !== $postValue['FK_user_id']) {
                    $this->redirect->getRedirect('/posts');
                }

                if (empty($postValue) === false) {
                    if ($this->postModel->postDeletion($getValuePostId) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été supprimé']);
                        $this->redirect->getRedirect('/posts');
                    }
                }
            }
        }//end if

    }//end postDeletion()


}//end class
