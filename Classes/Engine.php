<?php

class Engine{

    private $db;
    private $output;

    public function __construct($db, $output){
        $this->db = $db;
        $this->output = $output;

        session_start();
    }



    public function indexAction(){

        if($_SESSION['logged_in']==false){
            header('Location: index.php?site=login');
            return;
        }


        $this->output->indexOutput();
    }

    public function registerAction(){

        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        
        $error = "";
        if(empty($user) || empty($pass)){
            $error = "Bitte Benutzernamen und Passwort eingeben";
            $this->output->registerOutput($error);
            return; 
        }

        if(!empty($this->db->checkUser($user))){
            $error = "Benutzer existiert schon!";
            $this->output->registerOutput($error);
            return;
        }

        $this->db->insertUser($user,$pass);

    }


    public function loginAction(){

        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

        $error = "";
        if(empty($user) || empty($pass)){
            $error = "Bitte Benutzernamen und Passwort eingeben";
            $this->output->loginOutput($error);
            return; 
        }

        if(empty($this->db->checkUserPass($user, $pass))){
            $error = $this->output->loginOutput($error);
            $this->output->loginOutput($error);
            return;
        }
        
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user;
        
        header('Location: index.php');

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