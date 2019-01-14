<?php


class Database{

	private $db;
	
	public function __construct(){
		$this->db = new PDO("mysql:dbname=kosquiz;host=127.0.0.1", "root");
		
	}

	
	public function dropAndCreate(){
		$this->db->exec("DROP SCHEMA IF EXISTS lernquiz; CREATE SCHEMA lernquiz;USE lernquiz");
		
		$sql = "CREATE TABLE IF NOT EXISTS blalbla";
		$this->db->exec($sql);
	}
	
	/**
	 * insert chat message by user
	 */
	public function insertChat($user, $msg){
		$sql = $this->db->prepare("INSERT INTO chatmessage VALUES(null,'$','$msg','$user');");
		$sql->exec([$user,$pass]);
		
	}

	/**
	 * get last 50 chat messages
	 */
	public function getChat(){
		
		$sql = $this->db->prepare("SELECT * FROM chatmessage ORDER BY idChat DESC TOP 50;");
		$sql->execute();
		echo '<pre>';
		print_r($sql->fetchAll());
		echo '</pre>';
		return $sql->fetchAll();
	}
	
	/**
	 * insert 1 user with pass
	 */
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT INTO accounts VALUES(NOW(),'$user','$pass', 0)");
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
	 * select 1 user with pass
	 */
	public function checkUserPass($user, $pass){

	}
	
	public function debug(){
		$user = "Baum";
		$rows = $this->getChat();

	}
}