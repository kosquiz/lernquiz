<?php

require_once "Classes/Output.php";
require_once "Classes/Database.php";
require_once "Classes/Engine.php";

session_set_cookie_params('3600'); // 1 hour
session_start();

$out = new Output();
$db = new Database();
$engine = new Engine($db, $out);


$site = $_GET['site'];

switch($site){
	
	case "login":
		$engine->loginAction();
		break;

	case "doLogin":
		$engine->loginPostAction();
		break;

	case "register":
		$engine->registerAction();
		break;

	case "doRegister":
		$engine->registerPostAction();
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

	case "debug":
		$engine->debugAction();
		break;

	default:
		$engine->indexAction();
		break;
}