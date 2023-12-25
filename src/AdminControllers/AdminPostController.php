<?php

namespace src\AdminControllers;

use src\Entity\PostEntity;
use src\AdminModels\AdminPostModel;
use App\SuperGlobal;
use App\Redirect;
use src\AdminControllers\AdminCommentController;

// Admin Post Controller.
class AdminPostController
{

    /**
     *
     * @var $adminPostModel for AdminPostModel class
     */
    private $adminPostModel;

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
     * @var $adminCommentController for AdminCommentController class
     */
    private $adminCommentController;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminPostModel = new AdminPostModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();
        $this->adminCommentController = new AdminCommentController();

    }//end __construct()


    /**
     * Admin post list
     *
     * @return view
     */
    public function adminPostList()
    {
        $varValue = [];

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();
        $postList = $this->adminPostModel->adminGetPostList();

        if (empty($postList) === false) {
            $errors = [];
            $varValue['postList'] = $postList;
        }

        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostList.twig';
        $view['var'] = $varValue;
        return $view;

    }//end adminPostList()


    /**
     * Admin read post
     *
     * @return view
     */
    public function adminReadPost()
    {
        $varValue = [];
        $errors = [];

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
        }

        if (empty($getValuePostId) === false) {
            $post = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($post) === false) {
                $varValue['post'] = $post;
                $varValue['commentList'] = $this->adminCommentController->adminGetPostCommentList($getValuePostId);
            }
        }

        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminReadPost.twig';
        $view['var'] = $varValue;
        return $view;

    }//end adminReadPost()


    /**
     * Admin Post Modification
     *
     * @return view
     */
    public function adminPostModification()
    {
        $varValue = [];
        $errors = [];

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $postValue = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($postValue) === false) {
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
                    if ($this->adminPostModel->adminPostModification($postValue) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été modifié']);
                        $this->redirect->getRedirect('/admin/post/'.$getValuePostId.'/'.str_replace(' ', '-', $postValue['title']).'');
                    }
            } else {
                    $newPostValues = [];
                    if (empty($postValues) === false) {
                        foreach ($postValues as $key => $newValue) {
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
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostModification.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end adminPostModification()


    /**
     * Admin post deletion
     *
     * @return void
     */
    public function adminPostDeletion()
    {
        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $postValue = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($this->adminCommentController->adminCommentListDeletion($getValuePostId) === true) {
                    if ($this->adminPostModel->adminPostDeletion($getValuePostId) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été supprimé']);
                        $this->redirect->getRedirect('/admin/posts');
                    }
                }
            }
        }//end if

    }//end adminPostDeletion()


}//end class
