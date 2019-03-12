<?php

class Engine{

    private $db;
    private $output;

    public function __construct($db, $output){
        $this->db = $db;
        $this->output = $output;

        
    }



    public function indexAction(){

        if(!array_key_exists('logged_in', $_SESSION) || $_SESSION['logged_in']==false){
            header('Location: index.php?site=login');
            return;
        }


        if(array_key_exists('roomID', $_SESSION)){
            $this->output->gameBoardOutput();
            return;
        }
        else{
            /*
                'rooms' => [
                    [idGameRoom, isPrivate, Password],
                ]
            */
            $vars = ['rooms'=>$this->db->getGameRooms()];
            $this->output->indexOutput($vars);
            return;
        }

    }

    public function debugAction(){
        $games = [['roomName'=>'Raum 1', 'id'=>1],['roomName'=>'Raum 2', 'id'=>2],['roomName'=>'Raum 3', 'id'=>3]];
        $this->output->indexOutput($games);
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

        header('Location: index.php?site=login');

    }

    public function registerAction(){
        $this->output->registerOutput(false);
    }

    public function loginAction(){
        $this->output->loginOutput(false);
    }

    public function loginPostAction(){

        $user = $_POST['user'];
        $pass = $_POST['pass'];

        $error = "";
        if(empty($user) || empty($pass)){
            $error = "Bitte Benutzernamen und Passwort eingeben";
            echo $error;
            $this->output->loginOutput($error);
            return; 
        }

        echo "$user, $pass<br>";
        $userDB = $this->db->checkUserPass($user);
        if(!password_verify($pass, $userDB['Password'])){
            $error = "Falsche Kombination Benutzername und Passwort";
            echo $error;
            $this->output->loginOutput($error);
            return;
        }
        
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user;
        

        header('Location: index.php');

    }

    public function logoutAction(){
        unset($_SESSION['user']);
        unset($_SESSION['logged_in']);
        header('Location: index.php');
    }


    public function createGameRoomAction(){

        if(!array_key_exists('logged_in', $_SESSION)){
            header('Location: index.php');
            return;
        }


        //TODO NAME AND CREATOR
        //$private = $_POST['private'];
        //$password = $_POST['password'];
        $roomName = $_POST['roomName'];
        $private = 0;
        $password = "";
        
        //TODO RETURN NEW GAMEROOM ID
        $gameRoomID = $this->db->newGameRoom($roomName, 1, $private, $password, $_SESSION['user']);
        $_SESSION['roomID'] = $gameRoomID;
        header('Location: index.php');
    }

    public function joinGameAction(){
        
        if(!array_key_exists('logged_in', $_SESSION) || empty($_POST['roomID'])){
            header('Location: index.php');
            return;
        }

        //TODO if private, or passworded

        $_SESSION['roomID'] = $_POST['roomID'];

        header('Location: index.php');
    }

    public function leaveGameRoomAction(){
        unset($_SESSION['roomID']);
        header('Location: index.php');
    }

    /**
     * Helper Functions
     */

    private function checkActiveGames(){

    }

    /**
     * AJAX Actions
     */

    public function sendChatAjaxAction(){

        if($_SESSION['logged_in']==false){
            return;
        }

        $user = $_SESSION['user'];
        $msg = $_POST['msg'];
        $roomID = $_SESSION['roomID'];

        $this->db->insertChat($msg, $user, $roomID);

        echo json_encode(['success'=>true, 'vars'=>[$user, $msg, $roomID]]);

    }

    public function getChatAjaxAction(){
        
        if($_SESSION['logged_in']==false){
            return;
        }

        $user = $_SESSION['user'];
        $roomID = $_SESSION['roomID'];

        $chat = $this->db->getChat($roomID);

        echo json_encode(['success'=>true, 'chat'=>$chat]);

    }

    public function setUserActiveAjax(){

        if(!array_key_exists('user', $_SESSION)){
            echo json_encode(['success'=>false, 'error'=>'Nicht eingeloggt']);
            return;
        }
        $this->db->setUserActivity($_SESSION['user']);
    }

    public function getUserActiveAjax(){
        $users = $this->db->getActiveUsers();
        $ret = [];

        foreach($users as $u){
            $ret[] = $u['Username'];
        }

        echo json_encode(['success'=>true, 'users'=>$ret]);
    }



}