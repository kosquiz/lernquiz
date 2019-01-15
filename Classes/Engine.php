<?php

class Engine{

    private $db;
    private $output;

    public function __construct($db, $output){
        $this->db = $db;
        $this->output = $output;

        
    }



    public function indexAction(){

        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        if($_SESSION['logged_in']==false){
            //header('Location: index.php?site=login');
            return;
        }


        $this->output->gameBoardOutput();
    }

    public function debugAction(){
        $this->output->gameBoardOutput(false);
    }

    public function registerPostAction(){

        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        
        $error = "";
        if(empty($user) || empty($pass)){
            $error = "Bitte Benutzernamen und Passwort eingeben";
            echo $error;
            $this->output->registerOutput($error);
            return; 
        }

        if(!empty($this->db->checkUser($user))){
            $error = "Benutzer existiert schon!";
            echo $error;
            $this->output->registerOutput($error);
            return;
        }

        $this->db->insertUser($user,$pass);

        echo "hi $user $pass";
    }

    public function registerAction(){

        $this->output->registerOutput(false);
     
    }

    public function loginAction(){
        $this->output->loginOutput(false);
    }

    public function loginPostAction(){

        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

        $error = "";
        if(empty($user) || empty($pass)){
            $error = "Bitte Benutzernamen und Passwort eingeben";
            echo $error;
            $this->output->loginOutput($error);
            return; 
        }

        if(empty($this->db->checkUserPass($user, $pass))){
            $error = "Falsche Kombination Benutzername und Passwort";
            echo $error;
            $this->output->loginOutput($error);
            return;
        }
        
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user;
        

        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        //header('Location: index.php');

    }

    public function sendChatAjaxAction(){

        if($_SESSION['logged_in']==false){
            return;
        }

        $user = $_SESSION['user'];
        $msg = $_POST['msg'];

        $this->db->insertChat($user, $msg);

        echo json_encode(['success'=>true]);

    }

    public function getChatAjaxAction(){
        
        if($_SESSION['logged_in']==false){
            return;
        }

        $user = $_SESSION['user'];

        $chat = $this->db->getChat();

        echo json_encode(['success'=>true, 'chat'=>$chat]);

    }

}