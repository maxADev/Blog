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
     * Create post page
     *
     * @return view
     */
    public function adminPostCreation()
    {
        $errors;

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue = ['userAdmin' => $this->superGlobal->getCurrentUser()];

        if ($this->superGlobal->postExist() === true) {
            $post = [];
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            $postValue['FkUserId'] = $varValue['userAdmin']['id'];
            $postValues = new PostEntity($postValue);

            if (empty($errors) === true && $postValues->isValid() === true) {
                if ($this->adminPostModel->adminCreatePost($postValues) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été créé']);
                    $this->redirect->getRedirect('/admin/posts');
                }
            } else {
                $varValue['postValue'] = $postValue;
                $this->superGlobal->createFlashMessage($postValues->getError());
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
        $view['file'] = 'adminPostCreation.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end adminPostCreation()


    /**
     * Get user post
     *
     * @param $userId user id
     * @return void
     */
    public function getUserPost($userId)
    {
        if (empty($userId) === false) {
            $postList = $this->postModel->getUserPosts($userId);
            return $postList;
        }

    }//end getUserPost()


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

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostList.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
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

        if ($this->superGlobal->getDataExist('postId') === false) {
            $this->redirect->getRedirect('/admin/posts');
        }

        $getValuePostId = $this->superGlobal->getGetData('postId');

        $post = $this->adminPostModel->adminGetPost($getValuePostId);
        if (empty($post) === false) {
            $varValue['post'] = $post;
            $varValue['commentList'] = $this->adminCommentController->adminGetPostCommentList($getValuePostId);
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminReadPost.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
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

        if ($this->superGlobal->getDataExist('postId') === false) {
            $this->redirect->getRedirect('/admin/posts');
        }

        $getValuePostId = $this->superGlobal->getGetData('postId');
        $post = $this->adminPostModel->adminGetPost($getValuePostId);
        if (empty($post) === false) {
            $varValue['postValue'] = $post;
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            $postValue['id'] = $getValuePostId;
            $postValues = new PostEntity($postValue);

            if ($varValue['userAdmin']['id'] != $post['FK_user_id']) {
                $errors = ['type' => 'danger', 'message' => 'Ce n\'est pas votre post'];
            }

            if (empty($errors) === true && $postValues->isValid() === true) {
                    if ($this->adminPostModel->adminPostModification($postValue) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été modifié']);
                        $this->redirect->getRedirect('/admin/post/'.$getValuePostId.'/'.str_replace(' ', '-', $postValue['title']).'');
                    }
            } else {
                $varValue['postValue'] = $postValue;
                $this->superGlobal->createFlashMessage($postValues->getError());
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

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getDataExist('postId') === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $postValue = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($postValue) === false && $varValue['userAdmin']['id'] === $postValue['FK_user_id']) {
                if ($this->adminCommentController->adminCommentListDeletion($getValuePostId) === true) {
                    if ($this->adminPostModel->adminPostDeletion($getValuePostId) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été supprimé']);
                        $this->redirect->getRedirect('/admin/posts');
                    }
                }
            } else {
                $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Ce n\'est pas votre post']);
                $this->redirect->getRedirect('/admin/posts');
            }
        }//end if

    }//end adminPostDeletion()


}//end class
