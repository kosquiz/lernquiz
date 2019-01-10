<?php

require_once "Classes/Output.php";
require_once "Classes/Database.php";


$out = new Output();

$out->indexOutput(true);

$db = new Database();

$site = $_GET['site'];

switch($site){
	
	case "reset":
		$db->dropAndCreate();
		break;
		
	case "luisDebug":
		$db->debug();
		break;
}