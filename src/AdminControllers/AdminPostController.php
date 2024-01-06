<?php

namespace src\AdminControllers;

use src\Entity\PostEntity;
use src\AdminModels\AdminPostModel;
use App\SuperGlobal;
use App\Redirect;
use src\AdminControllers\AdminUserController;
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
        $image = '';

        $this->superGlobal->userIsAdmin();

        $varValue = ['userAdmin' => $this->superGlobal->getCurrentUser()];
        $postCategoryList = $this->adminPostModel->adminGetPostCategoryList();
        $varValue['postCategoryList'] = $postCategoryList;

        $postStatutList = $this->adminPostModel->adminGetPostStatutList();
        $varValue['postStatutList'] = $postStatutList;

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            $postValue['FKUserId'] = $varValue['userAdmin']['id'];
            $postValue['FKPostStatutId'] = $postValue['statut'];
            $postValue['FKCategoryId'] = $postValue['category'];

            $postValues = new PostEntity($postValue);

            if ($this->superGlobal->postFileExist() === true) {
                $image = $this->superGlobal->getFilePost();
                if ($this->checkImage($image) === true) {
                    $fileFormat = pathinfo($image['image']["name"], PATHINFO_EXTENSION);
                    $postValues->setPostImage('postImage.'.$fileFormat);
                } else {
                    $errors = [
                               'type'    => 'danger',
                               'message' => "Votre image n'est pas valide",
                              ];
                }
            }

            if ($this->adminPostModel->adminCheckPostSlug($postValues->getPostSlug()) !== true) {
                $errors = [
                           'type'    => 'danger',
                           'message' => "Ce titre est déjà pris, merci de changer",
                          ];
            }

            if (empty($errors) === true && $postValues->isValid() === true) {
                $checkPostCreation = $this->adminPostModel->adminCreatePost($postValues);
                if ($checkPostCreation['return'] === true && empty($checkPostCreation['lastPostId']) === false) {
                    if (empty($image) === false) {
                        $this->uploadImage($image, $checkPostCreation['lastPostId'], $fileFormat);
                    }

                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été créé']);
                    $this->redirect->getRedirect('/admin/posts');
                } else {
                    $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Erreur lors de la création du post']);
                }
            } else {
                $varValue['postValue'] = $postValue;
                $this->superGlobal->createFlashMessage($postValues->getError());
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $varValue['formSetting'] = [
                                    "image"    => ["label" => "Image",   "type"     => "file",     "placeholder" => "Votre image(non obligatoire)"],
                                    "title"    => ["label" => "Titre",   "type"     => "text",     "placeholder" => "Titre du post"],
                                    "chapo"    => ["label" => "Chapo",   "type"     => "text",     "placeholder" => "Chapo du post"],
                                    "content"  => ["label" => "Contenu", "type"     => "textarea", "placeholder" => "Contenu du post"],
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
        $newPostList = [];

        $this->superGlobal->userIsAdmin();

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();
        $varValue['postFilterList'] = [
                                       ['value' => '1', 'name' => "Mes posts"],
                                       ['value' => '2', 'name' => 'Publié'],
                                       ['value' => '3', 'name' => 'Brouillon'],
                                       ['value' => '4', 'name' => 'Désactivé'],
                                       ['value' => '5', 'name' => 'Tous'],
                                      ];
        $postList = $this->adminPostModel->adminGetPostList();

        if (empty($postList) === false) {
            $varValue['postList'] = $postList;
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $postValueFilter = (int) $postValue['postFilter'];
            $varValue['postFilterValue'] = $postValueFilter;

   
            foreach ($postList as $post) {
                if ($postValueFilter === 1) {
                    if ($post['FK_user_id'] === $varValue['userAdmin']['id']) {
                        $newPostList[] = $post;
                    }
                } else if ($postValueFilter === 2) {
                    if ((int) $post['FK_post_statut_id'] === 2) {
                        $newPostList[] = $post;
                    }
                } else if ($postValueFilter === 3) {
                    if ((int) $post['FK_post_statut_id'] === 1) {
                        $newPostList[] = $post;
                    }
                } else if ($postValueFilter === 4) {
                    if ((int) $post['FK_post_statut_id'] === 3) {
                        $newPostList[] = $post;
                    }
                } else {
                    $newPostList[] = $post;
                }

            }//end if
            $varValue['postList'] = $newPostList;
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

        $this->superGlobal->userIsAdmin();

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getDataExist('postSlug') === false) {
            $this->redirect->getRedirect('/admin/posts');
        }

        $getValuePostSlug = $this->superGlobal->getGetData('postSlug');
        $getValuePostId = $this->adminPostModel->adminGetPostId($getValuePostSlug);

        $post = $this->adminPostModel->adminGetPost($getValuePostId['id']);
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
        $image = '';
        $varValue = [];
        $errors = [];

        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getDataExist('postId') === false) {
            $this->redirect->getRedirect('/admin/posts');
        }

        $postCategoryList = $this->adminPostModel->adminGetPostCategoryList();
        $varValue['postCategoryList'] = $postCategoryList;

        $postStatutList = $this->adminPostModel->adminGetPostStatutList();
        $varValue['postStatutList'] = $postStatutList;

        $adminUserController = new AdminUserController();
        $userList = $adminUserController->getUserList();
        $varValue['userList'] = $userList;

        $getValuePostId = $this->superGlobal->getGetData('postId');
        $post = $this->adminPostModel->adminGetPost($getValuePostId);
        if (empty($post) === false) {
            $varValue['postValue'] = $post;
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            $postValue['id'] = $getValuePostId;
            $postValue['FKUserId'] = $postValue['user'];
            $postValue['FKPostStatutId'] = $postValue['statut'];
            $postValue['FKCategoryId'] = $postValue['category'];

            $postValues = new PostEntity($postValue);

            if ($varValue['userAdmin']['id'] !== $post['FK_user_id']) {
                $errors = [
                        'type'    => 'danger',
                        'message' => 'Ce n\'est pas votre post',
                        ];
            }

            if ($this->superGlobal->postFileExist() === true) {
                $image = $this->superGlobal->getFilePost();
                if ($this->checkImage($image) === true) {
                    $fileFormat = pathinfo($image['image']["name"], PATHINFO_EXTENSION);
                    $postValues->setPostImage('postImage.'.$fileFormat);
                } else {
                    $errors = [
                            'type'    => 'danger',
                            'message' => "Votre image n'est pas valide",
                            ];
                }
            } else {
                $postValues->setPostImage($post['image']);
            }

            if ($post['title'] !== $postValues->getPostTitle() && $this->adminPostModel->adminCheckPostSlug($postValues->getPostSlug()) !== true) {
                $errors = [
                           'type'    => 'danger',
                           'message' => "Ce titre est déjà pris, merci de changer",
                          ];
            }

            if (empty($errors) === true && $postValues->isValid() === true) {
                if ($this->adminPostModel->adminPostModification($postValues) === true) {
                    if (empty($image) === false) {
                        $this->deleteImage($getValuePostId);
                        $this->uploadImage($image, $getValuePostId, $fileFormat);
                    }

                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été modifié']);
                    $this->redirect->getRedirect('/admin/readPost/'.$postValues->getPostSlug().'');
                }
            } else {
                $varValue['postValue'] = $postValue;
                $this->superGlobal->createFlashMessage($postValues->getError());
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $varValue['formSetting'] = [
                                    "image"   => ["label" => "Image",   "type" => "file",     "placeholder" => "Votre image(non obligatoire)"],
                                    "title"   => ["label" => "Titre",   "type" => "text",     "placeholder" => "Titre du post"],
                                    "chapo"   => ["label" => "Chapo",   "type" => "text",     "placeholder" => "Chapo du post"],
                                    "content" => ["label" => "Contenu", "type" => "textarea", "placeholder" => "Contenu du post"],
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
     * Admin Post Admin Modification
     *
     * @return view
     */
    public function adminPostAdminModification()
    {
        $varValue = [];
        $errors = [];

        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getDataExist('postId') === false) {
            $this->redirect->getRedirect('/admin/posts');
        }

        $postCategoryList = $this->adminPostModel->adminGetPostCategoryList();
        $varValue['postCategoryList'] = $postCategoryList;

        $postStatutList = $this->adminPostModel->adminGetPostStatutList();
        $varValue['postStatutList'] = $postStatutList;

        $postStatutList = $this->adminPostModel->adminGetPostStatutList();
        $varValue['postStatutList'] = $postStatutList;

        $adminUserController = new AdminUserController();
        $userList = $adminUserController->getUserList();
        $varValue['userList'] = $userList;

        $getValuePostId = $this->superGlobal->getGetData('postId');
        $post = $this->adminPostModel->adminGetPost($getValuePostId);
        if (empty($post) === false) {
            $varValue['postValue'] = $post;
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            $postValue['id'] = $getValuePostId;
            $postValue['FKUserId'] = $postValue['user'];
            $postValue['FKPostStatutId'] = $postValue['statut'];

            if (empty($errors) === true) {
                if ($this->adminPostModel->adminPostAdminModification($postValue) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le post a bien été modifié']);
                    $this->redirect->getRedirect('/admin/readPost/'.$post['slug'].'');
                }
            }
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostAdminModification.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end adminPostAdminModification()


    /**
     * Admin post deletion
     *
     * @return void
     */
    public function adminPostDeletion()
    {
        $varValue = [];

        $this->superGlobal->userIsAdmin();
        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));

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


    /**
     * Upload Image
     *
     * @param $image      image value
     * @param $lastPostId lastPostId value
     * @param $fileFormat fileFormat value
     * @return void
     */
    public function uploadImage($image, $lastPostId, $fileFormat)
    {
        if (mkdir("public/upload/".$lastPostId) === true) {
            move_uploaded_file($image["image"]["tmp_name"], "public/upload/".$lastPostId."/postImage.".$fileFormat);
        } else {
            $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Le dossier pour l\'image ne peut pas être créé']);
        }

    }//end uploadImage()


    /**
     * Check Image
     *
     * @param $image image value
     * @return void
     */
    public function checkImage($image)
    {
        $return = false;
        $errors = [];
        $formatAllowed = [
                          'jpg'  => 'image/jpg',
                          'jpeg' => 'image/jpeg',
                          'gif'  => 'image/gif',
                          'png'  => 'image/png',
                         ];
        $fileName = $image['image']["name"];
        $fileType = $image['image']["type"];
        $fileSize = $image['image']["size"];

        $fileFormat = pathinfo($fileName, PATHINFO_EXTENSION);
        if (array_key_exists($fileFormat, $formatAllowed) === false) {
            $errors = [
                       'type'    => 'danger',
                       'message' => 'Ce type d\'image n\'est pas autorisé',
                      ];
        }

        $maxsize = ((5 * 1024) * 1024);
        if ($fileSize > $maxsize) {
            $errors = [
                       'type' => 'danger',
                       'message' => 'Le poids ne doit pas dépasser 5mo',
                      ];
        }

        if (in_array($fileType, $formatAllowed) === false) {
            $errors = [
                       'type' => 'danger',
                       'message' => 'Ce type MIME n\'est pas autorisé',
                      ];
        }

        if (empty($errors) === true) {
            $return = true;
        } else {
            $this->superGlobal->createFlashMessage($errors);
        }

        return $return;

    }//end checkImage()


    /**
     * Delete Image
     *
     * @param $postId postId value
     * @return void
     */
    public function deleteImage($postId)
    {
        $this->superGlobal->userIsAdmin();

        if (file_exists("public/upload/".$postId) === true) {
            $files = glob("public/upload/".$postId.'/*');

            foreach ($files as $file) {
                if (is_file($file) === true) {
                    unlink($file);
                }
            }

            rmdir("public/upload/".$postId);
        }

    }//end deleteImage()


}//end class
