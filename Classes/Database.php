<?php

use PDO;

class Database{

	private $db;

	public function __construct(){
		$this->db = new PDO("mysql:host=127.0.0.1", "root");
		
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

	}

	/**
	 * get last 50 chat messages
	 */
	public function getChat(){
		
		$sql = $this->db->prepare("SELECT ALL ETC WHERE = ? ");
		$sql->exec([$username]);
		
		return $sql->fetchAll();
		
	}
	
	/**
	 * insert 1 user with pass
	 */
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT VALUES(?,?) ");
		$sql->exec([$user,$pass]);
	
	}

	/**
	 * select 1 user with name
	 */
	public function checkUser($user){

	}

	/**
	 * select 1 user with pass
	 */
	public function checkUserPass($user, $pass){

	}
	
	public function debug(){
		$rows = $this->getChat();
		
		echo "<pre>";
		print_r($rows);
		echo "</pre>";
	}
}