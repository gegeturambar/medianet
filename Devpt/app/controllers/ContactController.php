<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ContactController extends Controller
{
    public function __construct()
    {
        $this->path_source = ROOT_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'upload';
    }

    public function indexAction(){
        //var_dump($_SERVER['SERVER_NAME']);die();
        //ICI first check if connected, if not redirect
        if(!$_SESSION['user']){
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/connectuser');
            die();
        }

        $modelContact = new Contact();
        // get directories tree from table
        $directoryModel   = new Directories();
        //$this->view->directories = $directoryModel->fetchAll("id");
        $this->view->directories = $directoryModel->fetchAllByAccess('USER',"id");
    }

    protected $path_source = '';
}
