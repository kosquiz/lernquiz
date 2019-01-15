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
		$sql->exec([$user,$pass]);
		
	}

	/**
	 * get last 50 chat messages
	 */
	public function getChat(){
		
		$sql = $this->db->prepare("SELECT * FROM chatmessage ORDER BY idChat DESC LIMIT 50;");
		$sql->execute();

		$rows = $sql->fetchAll();

		return $rows;

	}
	
	/**
	 * insert 1 user with pass
	 */
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT INTO accounts VALUES(NOW(), ?, ?, 0)");
		$sql->exec([$user,$pass]);
	
	}

	/**
	 * select 1 user with name
	 */
	public function checkUser($user){
		$sql = $this->db->prepare("SELECT * FROM accounts WHERE Username Like ?;");
		$sql->execute([$user]);
		$rows = $sql->fetchAll();

		return $rows;
	
	}
	
	/**
	 * set user activity  active
	 */
	public function setUserActive($user){
		$sql = $this->db->prepare("UPDATE accounts SET IsActive = 1 WHERE Username = ?;");
		$sql->execute([$user]);
	}
	
	/**
	 * set user activity  Inactive
	 */
	public function setUserInactive($user){
		$sql = $this->db->prepare("UPDATE accounts SET IsActive = 0 WHERE Username = ?;");
		$sql->execute([$user]);

	}

	/**
	 * select 1 user with pass
	 */
	public function checkUserPass($user, $pass){

	}
	
	public function debug(){
		$user = "Baum";
		$rows = $this->setUserActive($user);

	}
}