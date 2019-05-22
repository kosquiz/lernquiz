<?php

class AjaxController
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
     * AJAX Actions
     */

    /**
     * Helper function to verify if players turn
     */
    private function verifyAjax(){
        //TURN?
        $gameID = $this->db->getCurrentGameID($_SESSION['roomID']);
        if(empty($gameID)){
            echo json_encode(['success'=>false, 'message'=>'Invalid Game!']);
            die;
        }
        else
            $gameID = $gameID['idGame'];

        $player = $this->engine->whichPlayerTurn($gameID)['player'];
        $questionID = $_POST['id'];
        
        
        if($player != $_SESSION['user']){
            echo json_encode(['success'=>false, 'message'=>'User not logged in!']);
            die;
        }

        return ['player'=>$player, 'gameID'=>$gameID];
   }

   /**
    * User answers question by clicking answer 
    */
   public function answerQuestionAjaxAction(){
       $res = $this->verifyAjax();
       $player = $res['player'];
       $gameID = $res['gameID'];

       $answerPos = $_POST['id'];


       //time to answer
       $log = $this->db->getGameLog($gameID);
       if($log[count($log)-1]['EventName']=='uncoverQuestion'){
           
           $questionPos = $log[count($log)-1]['EventVal1'];
           $questionID = $this->db->getQuestionAtPos($gameID, $questionPos)['EventVal2'];
           $qAnswers = $this->db->getAnswers($questionID);
           $i = 0;
           echo $questionPos;print_r($qAnswers);
           $debugAnswer = true;
           foreach($qAnswers as $answer){
               $i++;
               if($i!=$answerPos)
                   continue;
               
               $this->db->insertGameLog('playerAnswered', $gameID, $player, "");
               if($answer['Correct']==1){
                   $debugAnswer = false;
                   $this->db->insertGameLog('addPoints', $gameID, $player, 10);
                   $message = "Spieler ". $player ." hat eine Frage richtig beantwortet!";
                   $this->db->insertChat($message, "admin", $_SESSION['roomID']);
               }
               else{
                   $debugAnswer = false;
                   $message = "Spieler ". $player ." hat eine Frage falsch beantwortet!";
                   $this->db->insertChat($message, "admin", $_SESSION['roomID']);
               }
           }
           if($debugAnswer){
               $this->db->insertGameLog('playerAnswered', $gameID, $player, "");
               $message = "Spieler ". $player ." hat eine ungültige Antwort abgegeben!";
               $this->db->insertChat($message, "admin", $_SESSION['roomID']);
           }

       }
      

      
   }

    /**
     * uncovers one question on the board, activated by user click
     */
   public function uncoverAjaxAction(){
       $res = $this->verifyAjax();
       $player = $res['player'];
       $gameID = $res['gameID'];

       $questionID = $_POST['id'];
       //QUESTION OPEN?
       $question = $this->engine->openQuestion($gameID);
       if(empty($question)){
           $this->db->insertGameLog('uncoverQuestion', $gameID, $questionID, "");
           $message = "Spieler ". $player ." hat eine Frage aufgedeckt!";
           $this->db->insertChat($message, "admin", $_SESSION['roomID']);
       }

       echo json_encode(['success'=>true]);

   }

   /**
    * User sends chat message
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

   /**
    * Returns chat history
    */
   public function getChatAjaxAction(){
       
       if($_SESSION['logged_in']==false){
           return;
       }

       $user = $_SESSION['user'];
       $roomID = $_SESSION['roomID'];

       $chat = $this->db->getChat($roomID);

       echo json_encode(['success'=>true, 'chat'=>$chat]);

   }

   /**
    * set user active in last 30 seconds
    */
   public function setUserActiveAjaxAction(){

       if(!array_key_exists('user', $_SESSION)){
           echo json_encode(['success'=>false, 'error'=>'Nicht eingeloggt']);
           return;
       }
       $this->db->setUserActivity($_SESSION['user']);
       echo "user set active";
   }

   /**
    * get active users
    */
   public function getUserActiveAjaxAction(){
       $users = $this->engine->getUsersInRoom();
       echo json_encode(['success'=>true, 'users'=>$users]);
   }

   /**
    * run game loop and return game state as json
    */
   public function gameTickAjaxAction(){
       $gameID = $this->db->getCurrentGameID($_SESSION['roomID']);
       $board = $this->engine->getGameBoard($gameID['idGame']);

       $this->engine->gameTick($gameID['idGame']);

       $res = ['board'=>$board['board'], 'answers'=>$board['answers']];
       echo json_encode($board, JSON_UNESCAPED_UNICODE );
      
       die();

       echo json_encode(['res'=>'res', 'gameID'=>$gameID['idGame'], 'roomID'=>$_SESSION['roomID']]);
   }




}