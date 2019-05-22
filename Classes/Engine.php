<?php

class Engine{

    private $db;
    private $output;

    public function __construct($db, $output){
        $this->db = $db;
        $this->output = $output;

        
    }



    /**
     * Helper Functions
     */

    /**
     * List active rooms
     */
    public function checkActiveGames(){
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
    public function initGameBoard($gameID){
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

    /**
     * get questionID from questionPosition on board
     */
    public function getQuestionID($gameID, $questionPos){
        $log = $this->db->getGameLog($gameID);
        
        foreach($log as $l){
            print_r($l);
            if($l['EventName']=='setQuestion')
                return $l['EventVal2'];
        }
    }

    /**
     * go over gamelog and generate game board and questions
     */
    public function getGameBoard($gameID){
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
                    $questionPos = $l['EventVal1'];
                    $questionID = $this->db->getQuestionAtPos($gameID, $questionPos)['EventVal2'];
                    $qAnswers = $this->db->getAnswers($questionID);
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

    /**
     * pick next player if turn is over
     */
    public function gameTick($gameID){
        $log = $this->db->getGameLog($gameID);

        $whichPlayer = $this->whichPlayerTurn($gameID);
        $player = $whichPlayer['player'];
        $turns = $whichPlayer['turns'];

        $l = $log[count($log)-1];

        if($l['EventName']=='playerAnswered'){
            $board = $this->getGameBoard;
        }


        //PICK NEXT PLAYER
        if(empty($player)){
            $player = $this->pickNextPlayer($gameID, $turns);
            $this->db->insertGameLog('playerTurn', $gameID, $player, "");
            $message = "Spieler " . $player . " ist jetzt am Zug!";
            $this->db->insertChat($message, "admin", $_SESSION['roomID']);
        }

    }

    /**
     * pick next player turn
     */
    public function pickNextPlayer($gameID, $turns){
        $users = $this->getUsersInRoom();
        $userCount = count($users);
        $nextUser = $users[$turns%$userCount];
        return $nextUser;

    }

    /**
     * get all users in one room
     */
    public function getUsersInRoom(){
        $users = $this->db->getActiveUsers();
        $active = [];
        foreach($users as $user){
            if($user['GameRoom_idGameRoom'] == $_SESSION['roomID'])
                $active[] = $user['Username'];
        }
        return $active;
    }

    /**
     * returns which players turn it is
     */
    public function whichPlayerTurn($gameID){
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
        
        return ['player'=>$player, 'turns'=>$turns];
    }

    /**
     * returns a question if one is open currently
     */
    public function openQuestion($gameID){
        $log = $this->db->getGameLog($gameID);

        $question = "";

        foreach($log as $l){
            switch($l['EventName']){
                case 'uncoverQuestion':
                    $question = $l['EventVal1'];
                    break;
                case 'playerAnswered':
                    $question = "";
                    break;
            }
        }

        return $question;
        
    }


}