<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class DirectoryController extends Controller
{
    const BADNAME_MSG = "Le nom de ce répertoire n'est pas valide";

    public function __construct()
    {
    }

    public function indexAction(){
        Utils::checkAdminAccess();
        $directoryModel = new Directories();
        $this->view->directories = $directoryModel->fetchAllButOne(1,"id",true);

        $this->view->title = "Gestion des répertoires";
    }

    protected function checkDirectoryName($name){
        $pattern = '/^[a-zA-Z]+$/';
        return preg_match($pattern,$name);
    }

    public function createdirectoryAction(){
        Utils::checkAdminAccess();
        //die('io');
        if($this->_getParam("name")){
            if(!$this->checkDirectoryName($this->_getParam("name"))) {
                Utils::addMsg(self::BADNAME_MSG, Utils::FAIL_MSG);
            }else {
                $this->createdirectory();
                header('Location:http://' . $_SERVER['HTTP_HOST'] . '/listdirectory');
            }
        }
        $this->view->title = "Créer un répertoire";
        $this->view->button_title = "Créer le répertoire";
        $this->view->msg_success = "Le répertoire a bien été créé";
        $this->view->urlAjax = 'createdirectoryajax';
        $directoriesModel = new Directories();
        $this->view->directories = $directoriesModel->fetchAll();
        $this->view->roles = Utils::getConf()['security']['roles'];
        $this->view->extensions = Utils::getConf()['import']['extensions'];
    }

    public function updatedirectoryAction(){
        Utils::checkAdminAccess();

        $id = $this->_getParam("id");
        $directoryModel = new Directories();
        $directory = $directoryModel->fetchOne($id);
        if(!$directory)
            throw new Exception("directory with id $id does not exists");

        if($this->_getParam("name")){
            if(!$this->checkDirectoryName($this->_getParam("name"))) {
                Utils::addMsg(self::BADNAME_MSG, Utils::FAIL_MSG);
            }else {
                $ret = $this->updateDirectory($directory);
                if ($ret['error'])
                    Utils::addMsg($ret['error_msg'], Utils::FAIL_MSG);
            }
        }

        $directoriesModel = new Directories();

        $this->view->directories = $directoriesModel->fetchAllButOne($id);

        $this->view->roles = Utils::getConf()['security']['roles'];

        $this->view->extensions = Utils::getConf()['import']['extensions'];

        $this->view->directory = $directory;
        $this->view->title = "Modifier un répertoire";
        $this->view->button_title = "Modifier le répertoire";
        $this->view->msg_success = "Le répertoire a bien été modifié";
        $this->view->urlAjax = 'updatedirectoryajax';

    }

    protected function createdirectory(){

        $name = $this->_getParam('name');

        $access = $this->_getParam('access');

        $parentid = $this->_getParam('parentid',1);

        $extensions = $this->_getParam('extensions');

        $directoryModel   = new Directories();

        $parent = $parentid ? $directoryModel->fetchOne($parentid) : "";

        $parentpath = $parent ? $parent->path :  Utils::getConf()['import']['path_dest'];

        // create path auto
        $path = $parentpath . "\\".$name;

        $extensions = count($extensions) ? implode(',',$extensions) : "";
        $error = false;
        $errors_msg = "";


        // check if directory exists
        if($directoryModel->fetchOneByNameAndParent($name,$parentid)){
            $errors_msg .= "Ce répertoire existe déjà";
            $error = true;
        }else{
            try{
                //create file in upload path
                if(!mkdir($path)){
                    $errors_msg .= "Ce répertoire n'a pu être créé";
                    $error = true;
                }
            }catch(Exception $ex){
                $errors_msg .= "Ce répertoire n'a pu être créé";
                $error = true;
            }
            if(!$error) {
                try {
                    $ret = $directoryModel->save(array('name' => $name, 'parentid' => $parentid, 'path' => $path, 'access' => $access, 'extensions' => $extensions));
                    $error = !$ret;

                    if ($error)
                        $errors_msg .= "Le répertoire n'a pas pu être enregistré";
                } catch (Exception $ex) {
                    $error = true;
                    $errors_msg .= "Le répertoire n'a pas pu être enregistré, avec l'exception =>" . $ex->getMessage();
                }
            }
        }
        if($error)
            Utils::addMsg($errors_msg,Utils::FAIL_MSG);
    }

    public function updatedirectoryajaxAction(){
        Utils::checkAdminAccess();

        $id = $this->_getParam('id');
        $directoryModel = new Directories();
        $directory = $directoryModel->fetchOne($id);
        if(!$directory)
            echo json_encode(array('error'=>true,'error_msg'=>'Ce répertoire ne peut être modifié car il n\'existe pas'));die();

        $ret = $this->updateDirectory($directory);
        echo json_encode($ret);die();
    }


    protected function updateDirectory(&$directory){
        $error = false;
        $error_msg = "";
        $id = $this->_getParam('id');
        $directoryModel = new Directories();

        $modif = false;
        $oldData = (array)$directory;
        $fields= array('id','name','parentid','access','extensions');
        foreach ($fields as $key ){
            $oldval = $directory->key;
            $directory->$key = $this->_getParam($key);

            $modifkey = $oldval != $directory->$key;
            $modif  = $modifkey || $modif;

            if($key == 'parentid' && $modifkey){
                $parent = $directory->$key ? $directoryModel->fetchOne($directory->$key) : "";

                $parentpath = $parent ? $parent->path :  Utils::getConf()['import']['path_dest'];

                $oldpath = $directory->path;
                $directory->path = $parentpath . "\\".$directory->name;
                if(file_exists($oldpath)) {
                    rename($oldpath,$directory->path);
                }elseif (!file_exists($directory->path)){
                    mkdir($directory->path);
                }
            }
        }
        /*
        $directory->name = $this->_getParam('name');

        $directory->access = $this->_getParam('access');

        $directory->parentid = $this->_getParam('parentid',1);

        $directory->extensions = $this->_getParam('extensions');
*/
        if(!$modif)
            return true;

        $directory->extensions = count($directory->extensions) ? implode(',',$directory->extensions) : "";

        //$data = ["id"=> $id,"name"=>$name,"access"=>$access,"parentid" => $parentid,"path"=> $path,"extensions"=>$extensions];

        try {
            $data = (array)$directory;
            $error = !($directoryModel->save($data));
            if($error)
                $error_msg .= "Le répertoire n'a pas pu être modifié";
        }catch (Exception $ex){
            $error = true;
            $error_msg .= "Le répertoire n'a pas pu être modifié, avec l'exception =>".$ex->getMessage();
        }
        return array('error'=>$error,'error_msg'=> $error_msg);
    }

    public function deletedirectoryajaxAction(){

        Utils::checkAdminAccess();

        $id = $this->_getParam('id');
        $error = false;
        $errors_msg = "";
        $directoryModel   = new Directories();
        // check if directory exists

        try {
        $error = !($directoryModel->delete($id));
            if($error)
                $errors_msg .= "Le répertoire n'a pas pu être supprimé";
        }catch (Exception $ex){
            $error = true;
            $errors_msg .= "Le répertoire n'a pas pu être supprimé, avec l'exception =>".$ex->getMessage();
        }
        echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
    }

    /*
    public function rebuilddirectorytreeajaxAction(){
        $directoryModel = new Directories();
        $error_msg = "";
        $error = false;
        try {
            $ret = $directoryModel->rebuild_tree(0, 1);
            $error = !$ret;
        }catch (Exception $ex){
            $error_msg = $ex->getMessage();
        }
        return json_encode(array("error"=>$error,'error_msg'=>$error_msg));
    }
    */
}
