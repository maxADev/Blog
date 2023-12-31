<?php

namespace src\Controllers;
use App\SuperGlobal;
use src\Controllers\PostController;

// Home Controller.
class HomeController extends SuperGlobal
{


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        $varValue = [];
        if (empty($this->getCurrentUser()) === false) {
            $varValue['user'] = $this->getCurrentUser();
        };

        $postController = new PostController;

        $postList = $postController->postListHome();

        if (empty($postList) === false) {
            $varValue['postList'] = $postList;
        }

        $view = [];
        $view['folder'] = 'templates\home';
        $view['file'] = 'home.twig';
        $view['var'] = $varValue;
        return $view;

    }//end index()


}//end class
