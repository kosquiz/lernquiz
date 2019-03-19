<?php

class Database{

	private $db;
	
	public function __construct(){
		$this->db = new PDO("mysql:dbname=kosquiz;host=127.0.0.1;charset=utf8", "root");
		
	}

	
	public function dropAndCreate(){
		$this->db->exec("DROP SCHEMA IF EXISTS lernquiz; CREATE SCHEMA lernquiz;USE lernquiz");
		
		$sql = "CREATE SCHEMA IF NOT EXISTS lernquiz";
		$this->db->exec($sql);
	}
	
	/**
	 * insert chat message by user
	 */
	public function insertChat($msg, $user, $gameRoomID){
		$sql = $this->db->prepare("INSERT INTO chatmessage(Time, Message, Accounts_Username, GameRoom_idGameRoom) VALUES(NOW(), ?, ?, ?);");
		$sql->execute([$msg,$user,$gameRoomID]);
		
	}

	/**
	 * get last 50 chat messages
	 */
	public function getChat($gameRoomID){
		
		$sql = $this->db->prepare("SELECT * FROM chatmessage WHERE GameRoom_idGameRoom = ? ORDER BY idChat DESC LIMIT 50;");
		$sql->execute([$gameRoomID]);

		$newestMsgs = $sql->fetchAll();

		return $newestMsgs;

	}

    /**
     * get all Users
     */
    public function getAllUsers(){

        $sql = $this->db->prepare("SELECT * FROM Accounts ORDER BY Username DESC");
        $sql->execute();

        $allGameRooms = $sql->fetchAll();

        return $allGameRooms;

    }
	
	/**
	 * insert 1 user with pass
	 */
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT INTO accounts  VALUES(?, ?, 0, NULL)");
		$sql->execute([$user,$pass]);
		
	}

	/**
	 * select 1 user with name
	 */
	public function checkUser($user){
		$sql = $this->db->prepare("SELECT * FROM accounts WHERE Username Like ?;");
		$sql->execute([$user]);
		$currUser = $sql->fetchAll();

		return $currUser;
	
	}
	
	/**
	 * set user activity  Inactive
	 */
	public function setUserActivity($user){
		
		$sql = $this->db->prepare("UPDATE accounts SET LastActivity = NOW() WHERE Username = ?;");
		$sql->execute([$user]);

	}

	/**
	 * gets users active in last 30 seconds
	 */
	public function getActiveUsers(){
		$inactive = Date("Y-m-d H:i:s", strtotime("-30 seconds"));
		
		$sql = $this->db->prepare("SELECT * FROM accounts WHERE LastActivity > '$inactive' ORDER BY Username ASC");
		$sql->execute();
		
		$activeUsers = $sql->fetchAll();

		return $activeUsers;
	}

    /**
     * change gamroom of user
     */
    public function setGameRoom($gameRoom,$user){

        $sql = $this->db->prepare("UPDATE accounts SET GameRoom_idGameRoom = ? WHERE Username = ?;");
        $sql->execute([$gameRoom, $user]);

    }

	/**
	 * select 1 user with pass
	 */
	public function checkUserPass($user){
		$sql = $this->db->prepare("SELECT * FROM accounts WHERE Username LIKE ?");
		$sql->execute([$user]);
		
		$currUser = $sql->fetch();
		
		return $currUser;

	}

	/**
	 * insert new game
	 */
	public function newGameRoom($roomname, $isActive, $isPrivate, $pass, $creator){
		$sql = $this->db->prepare("INSERT INTO GameRoom (GameRoomName, isActive, isPrivate, Password, Accounts_Username) VALUES(?, ?, ?, ?, ?)");
		$sql->execute([$roomname,$isActive,$isPrivate,$pass,$creator]);
		return $this->db->lastInsertId();
	}

	/**
	 * get all gamerooms, higher id first
	 */
	public function getGameRooms(){
		$sql = $this->db->prepare("SELECT * FROM GameRoom ORDER BY idGameRoom DESC");
		$sql->execute();
		
		$allGameRooms = $sql->fetchAll();
		
		return $allGameRooms;
	}

    /**
     * change gamroom to active
     */
    public function activateGameRoom($gameRoom){

        $sql = $this->db->prepare("UPDATE gameroom SET isActive = True WHERE idGameRoom = ?;");
        $sql->execute([$gameRoom]);

    }

    /**
     * change gamroom to inActive
     */
    public function deactivateGameRoom($gameRoom){

        $sql = $this->db->prepare("UPDATE gameroom SET isActive = False WHERE idGameRoom = ?;");
        $sql->execute([$gameRoom]);

    }

	/**
	 * insert game log entry
	 */
	public function insertGameLog($eventName, $idGame, $eventVal1, $eventVal2){
		$sql = $this->db->prepare("INSERT INTO gamelog(EventName, Date, Game_idGame, EventVal1, EventVal2) VALUES(?, NOW(), ?, ?, ?);");
		$sql->execute([$eventName, $idGame, $eventVal1, $eventVal2]);
	}

	/**
	 * get full game log
	 */
	public function getGameLog($idGame){
		$sql = $this->db->prepare("SELECT * FROM gamelog WHERE Game_idGame=?");
		$sql->execute([$idGame]);
		return $sql->fetchAll();
	}

	/**
	 * start a new game in game room
	 */
	public function insertGame($idGameRoom){
		$sql = $this->db->prepare("INSERT INTO game(GameRoom_idGameRoom) VALUES(?);");
		$sql->execute([$idGameRoom]);
		return $this->db->lastInsertId();
	}

	/**
	 * get gameID for gameRoomID
	 */
	public function getCurrentGameID($idGameRoom){
		$sql = $this->db->prepare("SELECT * FROM game WHERE GameRoom_idGameRoom=? ORDER BY idGame DESC LIMIT 1");
		$sql->execute([$idGameRoom]);
		return $sql->fetch();

	}
	
	/**
	 * get 4 questions by category
	 */
	public function getQuestions($category){
		$sql = $this->db->prepare("SELECT Question,idQuestion,Difficulty FROM 
		(SELECT * FROM Question WHERE Category LIKE ? ORDER BY RAND()) as rnd
		GROUP BY rnd.Difficulty 
		ORDER BY Difficulty ASC
		LIMIT 4");
		$sql->execute([$category]);
		
		$questions = $sql->fetchAll();

		return $questions;
	}

	/**
	 * get 4 categories
	 */
	public function getCategories(){
		$sql = $this->db->prepare("SELECT Category FROM Question GROUP BY Category ORDER BY RAND() LIMIT 4;");
		$sql->execute();
		
		$categorys = $sql->fetchAll();

		return $categorys;

	}

	/**
	 * get one question by id
	 */
	public function getQuestion($questionID){
		$sql = $this->db->prepare("SELECT Question,idQuestion,Difficulty FROM Question WHERE idQuestion=?");
		$sql->execute([$questionID]);
		return $sql->fetch();
	}

	/**
	 * get answers to question
	 */
	public function getAnswers($questionID){
		$sql = $this->db->prepare("SELECT * FROM answers WHERE Question_idQuestion=? ORDER BY idAnswer ASC");
		$sql->execute([$questionID]);
		return $sql->fetchAll();

	}

	
	public function debug(){
		$user = "test";
		$pass = "123";
		$category = "AWP";
        $msg = "Ich bin einge geile Schlange";
        $gameRoomID = "1";
		$rows = $this->insertChat($msg, $user, $gameRoomID);
		
		echo '<pre>';
		print_r($rows);
		echo '</pre>';

	}
}