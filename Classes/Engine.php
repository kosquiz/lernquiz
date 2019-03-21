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
            $this->checkActiveGames();
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
        $this->initGameBoard($gameID);

        $_SESSION['roomID'] = $gameRoomID;
        $this->db->setGameRoom($gameRoomID, $_SESSION['user']);
        header('Location: index.php');
    }

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

    public function leaveGameRoomAction(){
        unset($_SESSION['roomID']);
        $this->db->setGameRoom(NULL, $_SESSION['user']);
        header('Location: index.php');
    }

    /**
     * Helper Functions
     */

    private function checkActiveGames(){
        $rooms = $this->db->getGameRooms();
        $users = $this->db->getActiveUsers();

       
        foreach($rooms as $room){
            $active = false;
            foreach($users as $user){
                if($user['GameRoom_idGameRoom'] == $room['idGameRoom'])
                    $active = true;
            }
            if(!$active)
                $this->db->deactivateGameRoom($room['idGameRoom']);
        }
    }

    /**
     * build game board from database
     * save questions in states id->questionid
     */
    private function initGameBoard($gameID){
        $categories = $this->db->getCategories();
        //$gameboard = [ 1=>['show'=>'', 'value'=>1, 'hidden'=>'']];
        $gameboard = [];

        $questions = [];
        $i = 1;
        foreach($categories as $category){
            $gameboard[$i] = ['show'=>$category['Category'], 'value'=>0, 'hidden'=>false];
            //insertGameLog($eventName, $idGame, $eventVal1, $eventVal2){
            $this->db->insertGameLog('setCategory', $gameID, $i, $category['Category']);
            $questions = $this->db->getQuestions($category['Category']);

            $j = 1;
            foreach($questions as $question){
                $gameboard[$i+$j*4] = ['show'=>$question['Question'], 'value'=>$question['Difficulty'], 'hidden'=>true];
                $this->db->insertGameLog('setQuestion', $gameID, $i+$j*4, $question['idQuestion']);
                $j++;
            }

            $i++;
        }

    }

    private function getGameBoard($gameID){
        $log = $this->db->getGameLog($gameID);
        $gameboard = [];
        $answers = [];
        foreach($log as $l){

            switch($l['EventName']){
                case 'setCategory':
                    $gameboard[$l['EventVal1']] = ['show'=>$l['EventVal2'], 'value'=>0, 'hidden'=>false, 'pos'=> $l['EventVal1']];
                    break;
                
                case 'setQuestion':
                    $question = $this->db->getQuestion($l['EventVal2']);
                    $gameboard[$l['EventVal1']] = ['show'=>$question['Question'], 'value'=>$question['Difficulty'], 'hidden'=>true, 'pos'=> $l['EventVal1']];
                    break;

                case 'uncoverQuestion':
                    $gameboard[$l['EventVal1']]['hidden'] = false;
                    $qAnswers = $this->db->getAnswers($l['EventVal2']);
                    $i = 1;
                    foreach($qAnswers as $a){
                        $answers[$i] = ['id'=>$a['idAnswer'], 'show'=>$a['Answer'], 'correct'=>$a['Correct'], 'pos'=>$i];
                        $i++;
                    }
                    break;

                case 'playerTurn':
                    $answers = [];
                    break;
            }

        
        }

        return ['board'=>$gameboard, 'answers'=>$answers];

    }

    private function gameTick($gameID){
        $log = $this->db->getGameLog($gameID);

        $player = $this->whichPlayerTurn($gameID);

        $l = $log[count($log)-1];

        //ANSWER QUESTION
        if($l['EventName']=='playerAnswered'){
            $board = $this->getGameBoard;
        }


        //PICK NEXT PLAYER
        if(empty($playerID)){
            $player = $this->pickNextPlayer($gameID, $turns);
            $this->db->insertGameLog('playerTurn', $gameID, $player, "");
            $message = "Spieler " . $player . " ist jetzt am Zug!";
            $this->db->insertChat($message, "admin", $_SESSION['roomID']);
        }

    }

    private function pickNextPlayer($gameID, $turns){
        $users = $this->getUsersInRoom();
        $userCount = count($users);
        $nextUser = $users[$turns%$userCount];
        return $nextUser;

    }

    private function getUsersInRoom(){
        $users = $this->db->getActiveUsers();
        $active = [];
        foreach($users as $user){
            if($user['GameRoom_idGameRoom'] == $_SESSION['roomID'])
                $active[] = $user['Username'];
        }
        return $active;
    }

    private function whichPlayerTurn($gameID){
        $log = $this->db->getGameLog($gameID);
        $player = "";
        $turns = 0;
        foreach($log as $l){
            switch($l['EventName']){
                case 'playerTurn':
                    $player = $l['EventVal1'];
                    break;

                case 'playerAnswered':
                    $turns++;
                    $player = "";
                    break;
            }
        }
        return $player;
    }

    /**
     * AJAX Actions
     */

    public function uncoverAjaxAction(){
        //TURN?
        $gameID = $this->db->getCurrentGameID($_SESSION['roomID']);
        $this->whichPlayerTurn($gameID);
        
        //QUESTION OPEN?
    }

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

    public function setUserActiveAjaxAction(){

        if(!array_key_exists('user', $_SESSION)){
            echo json_encode(['success'=>false, 'error'=>'Nicht eingeloggt']);
            return;
        }
        $this->db->setUserActivity($_SESSION['user']);
        echo "user set active";
    }

    public function getUserActiveAjaxAction(){
        $users = $this->getUsersInRoom();
        echo json_encode(['success'=>true, 'users'=>$users]);
    }

    public function gameTickAjaxAction(){
        $gameID = $this->db->getCurrentGameID($_SESSION['roomID']);
        $board = $this->getGameBoard($gameID['idGame']);

        $this->gameTick($gameID['idGame']);

        $res = ['board'=>$board['board'], 'answers'=>$board['answers']];
        echo json_encode($board, JSON_UNESCAPED_UNICODE );
       
        die();

        echo json_encode(['res'=>'res', 'gameID'=>$gameID['idGame'], 'roomID'=>$_SESSION['roomID']]);
    }



}