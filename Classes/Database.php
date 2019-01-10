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
	
	public function getChat(){
		
		$sql = $this->db->prepare("SELECT ALL ETC WHERE = ? ");
		$sql->exec([$username]);
		
		return $sql->fetchAll();
		
	}
	
	public function insertUser($user,$pass){
		$sql = $this->db->prepare("INSERT VALUES(?,?) ");
		$sql->exec([$user,$pass]);
	
	}
	
	public function debug(){
		$rows = $this->getChat();
		
		echo "<pre>";
		print_r($rows);
		echo "</pre>";
	}
}