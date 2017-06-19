<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class UserController extends Controller
{
    public function __construct()
    {
    }

    public function indexAction(){
        Utils::checkAdminAccess();
        $userModel = new Users();
        $this->view->users = $userModel->fetchAll();
    }


    public function forgotpassAction(){

    }

    public function forgotpassmailAction(){
        $email =  $this->_getParam('email');
        $msg = "Si vous avez oublié votre mot de passe, veuillez cliquez sur le lien ci-contre  ";
        if($email) {
            $userModel = new Users();
            // check if user exists
            $user = $userModel->fetchOneByMail($email);
            if (!$user) {
                $this->error = true;
                $this->error_msg = "Il n'existe aucun utilisateur avec ce nom";
            } else {
                // check password
                // send mail with link to form edit user
                $link = "http://".$_SERVER['HTTP_HOST']."/updateuser?'".$user->id;
                $message = <<<EOT
    Cliquez sur le lien ci-contre pour changer votre mot de passe => $link
    Si vous n'avez pas fait de demande de changement de mot de passe, veuillez ne pas tenir compte de ce mail
    Cordialement
EOT;
                Utils::sendMail($msg,$dest,$from);
                Utils::addMsg("Un mail de renouvellement de mot de passe vous a été envoyé","notice");
                return json_encode(array("error"=>$error,"error_msg"=>$error_msg));
            }
        }
    }

    public function connectuserAction(){

        if(!Utils::checkGuestAccess()){
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/');
        }

        $this->view->title = "Connexion";
        $this->view->button_title = "Connectez vous";
        if($this->_getParam('mail') && $this->_getParam('password')) {
            $mail = $this->_getParam('mail');
            $pswd = $this->_getParam('password');
            $userModel = new Users();
            // check if user exists
            $user = $userModel->fetchOneByMail($mail);
            if (!$user) {
                $this->error = true;
                Utils::addMsg("Il n'existe aucun utilisateur avec ce nom",Utils::FAIL_MSG);
            } else {
                // check password
                if(!password_verify($pswd, $user->hash)){
                    $this->error = true;
                    Utils::addMsg("Le mot de passe ne correspond pas",Utils::FAIL_MSG);
                }else{
                    // redirect to file upload
                    $_SESSION['user'] = $user;
                    Utils::addMsg("Vous êtes désormais connecté en tant que $user->mail",Utils::SUCCESS_MSG);
                    header('Location:http://'.$_SERVER['HTTP_HOST'].'/formulaire');
                    die();
                }
            }

        }
    }

    public function logoutAction(){
        unset($_SESSION['user']);
        header('Location:http://'.$_SERVER['HTTP_HOST'].Utils::getConf()['security']['urllogin']);
        die();
    }


    protected $roles = array("USER","ADMIN");

    public function createuserAction(){
        Utils::checkAdminAccess();
        $this->view->title = "Créer un utilisateur";
        $this->view->button_title = "Créer l'utilisateur";
        $this->view->msg_success = "L'utilisateur a bien été créé";
        $this->view->urlAjax = 'createuserajax';
        $this->view->roles = $this->roles;
    }


    protected function checkPassword($password){
        $regex = '~^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$~';
        return preg_match($regex,$password);
    }

    protected function checkMail($mail){
        $regex = '~^[\w|.]*@s2hgroup.com$~';
        return preg_match($regex,$mail);
    }


    public function updateuserAction(){
        //Utils::checkAdminAccess();
        $id = $this->_getParam("id");
        $userModel = new Users();
        $user = $userModel->fetchOne($id);
        if(!$user)
            throw new Exception("user with id $id does not exists");

        $this->view->user = $user;
        $this->view->title = "Modifier un utilisateur";
        $this->view->button_title = "Modifier l'utilisateur";
        $this->view->msg_success = "L'utilisateur a bien été modifié";
        $this->view->urlAjax = 'updateuserajax';
        $this->view->roles = $this->roles;
    }

    public function createuserajaxAction(){

        Utils::checkAdminAccess();

        $mail = $this->_getParam('mail');

        if(!$this->checkMail($mail)){
            $error = true;
            $errors_msg = "Le mail n'est pas compatible";
            echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
        }

        $pswd = $this->_getParam('password');

        if(!$this->checkPassword($pswd)){
            $error = true;
            $errors_msg = "Le mot de passe n'est pas compatible";
            echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
        }

        $role = $this->_getParam('role');

        $error = false;
        $errors_msg = "";

        $userModel   = new Users();
        // check if user exists

        if($userModel->fetchOneByMail($mail)){
            $errors_msg .= "Un utilisateur avec le même nom existe déjà";
            $error = true;
        }else{
            $cost = Utils::getConf()['security']['cost'];
            $options = array('cost'=> $cost );
            $hash = password_hash($pswd, PASSWORD_BCRYPT, $options );

            try {
                $ret = $userModel->save(array('mail' => $mail, 'hash' => $hash, 'role' => $role));
                $error = !$ret;

                if($error)
                    $errors_msg .= "L'utilisateur n'a pas pu être enregistré";
            }catch (Exception $ex){
                $error = true;
                $errors_msg .= "L'utilisateur n'a pas pu être enregistré, avec l'exception =>".$ex->getMessage();
            }
        }
        echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
    }

    public function updateuserajaxAction(){

        //Utils::checkAdminAccess();

        $mail = $this->_getParam('mail');

        if(!$this->checkMail($mail)){
            $error = true;
            $errors_msg = "Le mail n'est pas compatible";
            echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
        }

        $pswd = $this->_getParam('password');

        if(!$this->checkPassword($pswd)){
            $error = true;
            $errors_msg = "Le mot de passe n'est pas compatible";
            echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
        }

        $role = $this->_getParam('role');

        $error = false;
        $errors_msg = "";
        $urlAjax = 'updateuserajax';

        $userModel   = new Users();
        // check if user exists
        $user = $userModel->fetchOneByMail($mail);
        $data = array( 'id' => $user->id, 'mail' => $mail, 'role' => $role );
        if($pswd) {
            $cost = Utils::getConf()['security']['cost'];
            $options = array('cost' => $cost);
            $pswd = password_hash($pswd, PASSWORD_BCRYPT, $options);
            $data['hash'] = $pswd;
        }

        try {
            $error = !($userModel->save($data));
            if($error)
                $errors_msg .= "L'utilisateur n'a pas pu être modifié";
        }catch (Exception $ex){
            $error = true;
            $errors_msg .= "L'utilisateur n'a pas pu être modifié, avec l'exception =>".$ex->getMessage();
        }
        echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
    }

    public function deleteuserajaxAction(){

        Utils::checkAdminAccess();

        $id = $this->_getParam('id');
        $error = false;
        $errors_msg = "";
        $userModel   = new Users();
        // check if user exists

        try {
        $error = !($userModel->delete($id));
            if($error)
                $errors_msg .= "L'utilisateur n'a pas pu être supprimé";
        }catch (Exception $ex){
            $error = true;
            $errors_msg .= "L'utilisateur n'a pas pu être supprimé, avec l'exception =>".$ex->getMessage();
        }
        echo json_encode(array('error'=>$error,'error_msg'=> $errors_msg));die();
    }

}
