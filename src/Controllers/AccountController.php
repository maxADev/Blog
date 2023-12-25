<?php

namespace src\Controllers;
use App\SuperGlobal;
use App\Redirect;
use src\Controllers\PostController;
use src\Controllers\CommentController;

// Account Controller.
class AccountController
{

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
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('/login');
        };

        $varValue['user'] = $this->superGlobal->getCurrentUser();


        if ($this->superGlobal->postExist() === true) {
            $post = [];
            $postValue = $this->superGlobal->getPost();
            if (isset($postValue['comment']) === true) {
                $commentController = new CommentController();
                $commentList = $commentController->getUserComment($varValue['user']['id']);
                if (empty($commentList) === false) {
                    $varValue['commentList'] = $commentList;
                }
            } elseif (isset($postValue['post']) === true) {
                $postController = new PostController();
                $postList = $postController->getUserPost($varValue['user']['id']);
                if (empty($postList) === false) {
                    $varValue['postList'] = $postList;
                }
            }
        }

       
        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\account';
        $view['file'] = 'account.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end index()


}//end class
