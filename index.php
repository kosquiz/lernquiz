<?php

require_once "Classes/Output.php";
require_once "Classes/Database.php";
require_once "Classes/Engine.php";

session_set_cookie_params('3600'); // 1 hour
session_start();

$out = new Output();
$db = new Database();
$engine = new Engine($db, $out);


$site = null;
if(array_key_exists('site', $_GET))
	$site = $_GET['site'];

switch($site){
	
	//LOGIN AND REGISTER
	case "logout":
		$engine->logoutAction();
		break;

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

	//CHAT
	case "getChat":
		$engine->getChatAjaxAction();
		break;

	case "sendChat":
		$engine->sendChatAjaxAction();
		break;
	
	case "setActive":
		$engine->setUserActiveAjaxAction();
		break;
	
	case "getActive":
		$engine->getUserActiveAjaxAction();
		break;

	//GAME
	case "gameTick":
		$engine->gameTickAjaxAction();
		break;

	case "uncoverQuestion":
		$engine->uncoverAjaxAction();
		break;

	case "answerQuestion":
		$engine->answerQuestionAjaxAction();
		break;

	//ROOMS
	case "createRoom":
		$engine->createGameRoomAction();
		break;

	case "joinRoom":
		$engine->joinGameAction();
		break;

	case "leaveRoom":
		$engine->leaveGameRoomAction();
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