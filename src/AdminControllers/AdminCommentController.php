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
        $commentSetting = '';

        $this->superGlobal->userIsAdmin();

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();
        $commentSetting = $this->superGlobal->getGetData('commentSetting');

        if ($commentSetting === 'invalid') {
            $commentList = $this->adminCommentModel->adminGetCommentByStatut(1);
        } else if ($commentSetting === 'valid') {
            $commentList = $this->adminCommentModel->adminGetCommentByStatut(2);
        } else {
            $commentList = $this->adminCommentModel->adminGetAllComment();
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $varValue['commentList'] = $commentList;
        $view = [];
        $view['folder'] = 'adminTemplates\comment';
        $view['file'] = 'adminCommentList.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end adminCommentList()


    /**
     * Admin comment validation
     *
     * @return void
     */
    public function adminCommentValidation()
    {
        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

        if ($this->superGlobal->getDataExist('commentId') === false) {
            $this->redirect->getRedirect('/admin/comments');
        }

        $commentId = $this->superGlobal->getGetData('commentId');

        if ($this->adminCommentModel->adminCommentValidate($commentId) === true) {
            $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été validé']);
            $this->redirect->getRedirect('/admin/comment/invalid');
        }

    }//end adminCommentValidation()


    /**
     * Admin comment invalidation
     *
     * @return void
     */
    public function adminCommentInvalidation()
    {
        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

        if ($this->superGlobal->getDataExist('commentId') === false) {
            $this->redirect->getRedirect('/admin/comments');
        }

        $commentId = $this->superGlobal->getGetData('commentId');

        if ($this->adminCommentModel->adminCommentInvalidate($commentId) === true) {
            $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été invalidé']);
            $this->redirect->getRedirect('/admin/comment/valid');
        }

    }//end adminCommentInvalidation()


    /**
     * Admin comment list deletion
     *
     * @param $postId post id
     * @return void
     */
    public function adminCommentListDeletion($postId)
    {
        $this->superGlobal->userIsAdmin();

        if (empty($postId) === false) {
            return $this->adminCommentModel->adminDeleteCommentList($postId);
        }//end if

    }//end adminCommentListDeletion()


    /**
     * Admin comment deletion
     *
     * @return void
     */
    public function adminCommentDeletion()
    {
        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

        if ($this->superGlobal->getDataExist('commentId') === false) {
            $this->redirect->getRedirect('/admin/comments');
        }

        $commentId = $this->superGlobal->getGetData('commentId');

        if ($this->adminCommentModel->adminDeleteComment($commentId) === true) {
            $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été supprimé']);
            $this->redirect->getRedirect('/admin/comments');
        } else {
            $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Le commentaire ne peut pas être supprimé']);
            $this->redirect->getRedirect('/admin/comments');
        }

    }//end adminCommentDeletion()


}//end class
