<?php

require_once "Classes/Output.php";
require_once "Classes/Database.php";
require_once "Classes/Engine.php";

$out = new Output();
$db = new Database();
$engine = new Engine($db, $out);


$site = $_GET['site'];

switch($site){
	
	case "login":
		$engine->loginAction();
		break;

	case "register":
		$engine->registerAction();
		break;

	case "getChat":
		$engine->getChatAjaxAction();
		break;

	case "sendChat":
		$engine->sendChatAjaxAction();
		break;
	
	case "reset":
		$db->dropAndCreate();
		break;
		
	case "luisDebug":
		$db->debug();
		break;

	case default:
		$engine->indexAction();
		break;
}