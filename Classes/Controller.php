<?php

class Controller
{
    
    private $db;
    private $output;
    private $engine;
    public function __construct($db, $output, $engine){
        $this->db = $db;
        $this->output = $output;
        $this->engine = $engine;
    }   


    /**
     * Main index action show login, roomlist or game room
     */
    public function indexAction(){

        if(!array_key_exists('logged_in', $_SESSION) || $_SESSION['logged_in']==false){
            header('Location: index.php?site=login');
            return;
        }


        
        if(array_key_exists('roomID', $_SESSION)){
            $info = $this->db->getGameroomInfo($_SESSION['roomID']);
            $this->output->gameBoardOutput(['roomName'=>$info['GameRoomName'], 'user'=>$info['Accounts_Username']]);
            return;
        }
        else{
            $this->engine->checkActiveGames();
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

    /***
     * Debug function
     */
    public function debugAction(){
        $games = [['roomName'=>'Raum 1', 'id'=>1],['roomName'=>'Raum 2', 'id'=>2],['roomName'=>'Raum 3', 'id'=>3]];
        $this->output->indexOutput($games);
    }

    /**
     * Register a new user
     */
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

    /**
     * Display register output
     */
    public function registerAction(){
        $this->output->registerOutput(false);
    }

    /**
     * Display login output
     */
    public function loginAction(){
        $this->output->loginOutput(false);
    }

    /**
     * Post action for the login
     */
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

    /**
     * Logout
     */
    public function logoutAction(){
        unset($_SESSION['user']);
        unset($_SESSION['logged_in']);
        unset($_SESSION['roomID']);
        header('Location: index.php');
    }


    /**
     * create a new game room
     */
    public function createGameRoomAction(){

        if(!array_key_exists('logged_in', $_SESSION)){
            header('Location: index.php');
            return;
        }

        if(array_key_exists('private', $_POST)){
            if($_POST['private']=='on')
                $private = 1;
        }
        else
            $private = 0;

        if(array_key_exists('roomPassword',$_POST))
            $password = $_POST['roomPassword'];
        else
            $password = "";
        
        $roomName = $_POST['roomName'];
       
        
        $gameRoomID = $this->db->newGameRoom($roomName, 1, $private, $password, $_SESSION['user']);
        $gameID = $this->db->insertGame($gameRoomID);
        $this->engine->initGameBoard($gameID);

        $_SESSION['roomID'] = $gameRoomID;
        $this->db->setGameRoom($gameRoomID, $_SESSION['user']);
        header('Location: index.php');
    }

    /**
     * Join a game room
     */
    public function joinGameAction(){
        
        if(!array_key_exists('logged_in', $_SESSION) || empty($_POST['roomID'])){
            header('Location: index.php');
            return;
        }

        //TODO if private
        //passwort
        $rooms = $this->db->getGameRooms();
        foreach($rooms as $room){
            if($room['idGameRoom']==$_POST['roomID'] && !empty($room['Password'])){
                if(array_key_exists('password', $_POST) && $_POST['password']!=$room['Password']){
                    header('Location: index.php');
                    return;
                }
                break;
            }
        }

        $_SESSION['roomID'] = $_POST['roomID'];
        $this->db->setGameRoom($_POST['roomID'], $_SESSION['user']);
        header('Location: index.php');
    }

    /**
     * Leave a game room
     */
    public function leaveGameRoomAction(){
        unset($_SESSION['roomID']);
        $this->db->setGameRoom(NULL, $_SESSION['user']);
        header('Location: index.php');
    }

    /**
     * admin actions
     */

     public function adminAction(){
         $questions = $this->db->getAllQuestions();

         $res = [];
         $categories = [];
         foreach($questions as $question){
             $answers = $this->db->getAnswers($question['idQuestion']);
             $res[] = ['question'=>$question, 'answers'=>$answers];

             if(!in_array($question['Category'], $categories))
                $categories[] = $question['Category'];
         }

         $this->output->adminPage(['res'=>$res, 'cats'=>$categories]);

     }

     public function adminAddQuestionAction(){
         $question = $_POST['question'];
         $cat = $_POST['category'];

         $this->db->insertQuestion($question, $cat);

         header('Location: index.php?site=adminPage');
     }

     public function adminDeleteQuestionAction(){
         $questionID = $_POST['questionID'];
         $this->db->deleteQuestion($questionID);

         
         header('Location: index.php?site=adminPage');
     }

     public function adminCorrectQuestionAction(){
         $answerID = $_POST['answerID'];
         $this->db->setAnswerActive($answerID);

         
         header('Location: index.php?site=adminPage');
     }

     public function adminAddAnswerAction(){
         $questionID = $_POST['questionID'];
         $answer = $_POST['answerText'];
         $this->db->insertAnswer($questionID, $answer);

         
         header('Location: index.php?site=adminPage');
     }

}