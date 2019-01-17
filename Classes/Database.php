<?php

class Database{

	private $db;
	
	public function __construct(){
		$this->db = new PDO("mysql:dbname=kosquiz;host=127.0.0.1", "root");
		
	}

	
	public function dropAndCreate(){
		$this->db->exec("DROP SCHEMA IF EXISTS lernquiz; CREATE SCHEMA lernquiz;USE lernquiz");
		
		$sql = "CREATE SCHEMA IF NOT EXISTS lernquiz";
		$this->db->exec($sql);
	}
	
	/**
	 * insert chat message by user
	 */
	public function insertChat($user, $msg){
		$sql = $this->db->prepare("INSERT INTO chatmessage(Time, Message, Accounts_Username) VALUES(NOW(), ?, ?);");
		$sql->execute([$msg,$user]);
		
	}

	/**
	 * get last 50 chat messages
	 */
	public function getChat(){
		
		$sql = $this->db->prepare("SELECT * FROM chatmessage ORDER BY idChat DESC LIMIT 50;");
		$sql->execute();

		$newestMsgs = $sql->fetchAll();

		return $newestMsgs;

	}
	
	/**
	 * insert 1 user with pass
	 */
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT INTO accounts VALUES(?, ?, 0)");
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
		
		$sql = $this->db->prepare("SELECT * FROM accounts WHERE LastActivity > '$inactive'");
		$sql->execute();
		
		$activeUsers = $sql->fetchAll();

		return $activeUsers;
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
	
	public function debug(){
		$user = "Baum";
		$pass = "123";
		$rows = $this->getActiveUsers();

	}
}