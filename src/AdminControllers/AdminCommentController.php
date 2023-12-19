<?php

namespace src\AdminControllers;

use src\Entity\CommentEntity;
use src\AdminModels\AdminPostModel;
use src\AdminModels\AdminCommentModel;
use App\SuperGlobal;
use App\Redirect;

// Admin Comment Controller.
class AdminCommentController extends SuperGlobal
{

    /**
     *
     * @var $adminCommentModel for AdminCommentModel class
     */
    private $adminCommentModel;

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
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminCommentModel = new AdminCommentModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Admin get post comment list
     *
     * @param $postId post id
     * @return comment
     */
    public function adminGetPostCommentList($postId)
    {
        $commentList = $this->adminCommentModel->adminGetCommentList($postId);

        return $commentList;

    }//end adminGetPostCommentList()


    /**
     * Admin comment list
     *
     * @return view
     */
    public function adminCommentList()
    {
        $varValue = [];
        $getValueCommentSetting;

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValueCommentSetting = $this->superGlobal->getGetData('commentSetting');
        }

        if ($getValueCommentSetting === 'invalid') {
            $commentList = $this->adminCommentModel->adminGetCommentByStatut(1);
        } else if ($getValueCommentSetting === 'valid') {
            $commentList = $this->adminCommentModel->adminGetCommentByStatut(2);
        } else {
            $commentList = $this->adminCommentModel->adminGetAllComment();
        }

        $varValue['commentList'] = $commentList;

        $view = [];
        $view['folder'] = 'adminTemplates\comment';
        $view['file'] = 'adminCommentList.twig';
        $view['var'] = $varValue;
        return $view;

    }//end adminCommentList()


    /**
     * Admin comment modification
     *
     * @return view
     */
    public function adminCommentModification()
    {
        $varValue = [];
        $getValueCommentId;

        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
        }

        if (empty($getValueCommentId) === true) {
            $this->redirect->getRedirect('/admin/comments');
        }
        $commentValue = $this->adminCommentModel->adminGetComment($getValueCommentId);
        $varValue['commentValue'] = $commentValue;

        if ($this->superGlobal->postExist() === true) {
            $postValue = [];

            $postValue = $this->superGlobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $postValue[$key] = $this->superGlobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $postValue['id'] = $getValueCommentId;
                var_dump($postValue);
                if ($this->adminCommentModel->adminCommentUpdate($postValue) === true) {
                    $this->redirect->getRedirect('/admin/comments');
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'adminTemplates\comment';
        $view['file'] = 'adminCommentModification.twig';
        $view['var'] = $varValue;
        return $view;

    }//end adminCommentModification()


    /**
     * Admin comment validation
     *
     * @return void
     */
    public function adminCommentValidation()
    {
        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        if ($this->superGlobal->getExist() === true) {
            $commentId = $this->superGlobal->getGetData('commentId');
        }

        if (empty($commentId) === false) {
            if ($this->adminCommentModel->adminCommentValidate($commentId) === true) {
                $this->redirect->getRedirect('/admin/comment/invalid');
            }
        }//end if

    }//end adminCommentValidation()


    /**
     * Admin comment invalidation
     *
     * @return void
     */
    public function adminCommentInvalidation()
    {
        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        if ($this->superGlobal->getExist() === true) {
            $commentId = $this->superGlobal->getGetData('commentId');
        }

        if (empty($commentId) === false) {
            if ($this->adminCommentModel->adminCommentInvalidate($commentId) === true) {
                $this->redirect->getRedirect('/admin/comment/valid');
            }
        }//end if

    }//end adminCommentInvalidation()


    /**
     * Admin comment list deletion
     *
     * @param $postId post id
     * @return void
     */
    public function adminCommentListDeletion($postId)
    {
        if ($this->superGlobal->userIsAdmin() === false) {
            $this->redirect->getRedirect('login');
        };

        if (empty($postId) === false) {
            return $this->adminCommentModel->adminDeleteCommentList($postId);
        }//end if

    }//end adminCommentListDeletion()


}//end class
