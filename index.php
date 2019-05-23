<?php

require_once "Classes/Output.php";
require_once "Classes/Database.php";
require_once "Classes/Engine.php";
require_once "Classes/Controller.php";
require_once "Classes/AjaxController.php";

session_set_cookie_params('3600'); // 1 hour
session_start();

$out = new Output();
$db = new Database();
$engine = new Engine($db, $out);
$ajaxContr = new AjaxController($db, $out, $engine);
$controller = new Controller($db, $out, $engine);


$site = null;
if(array_key_exists('site', $_GET))
	$site = $_GET['site'];

switch($site){
	
	//LOGIN AND REGISTER
	case "logout":
		$controller->logoutAction();
		break;

	case "login":
		$controller->loginAction();
		break;

	case "doLogin":
		$controller->loginPostAction();
		break;

	case "register":
		$controller->registerAction();
		break;

	case "doRegister":
		$controller->registerPostAction();
		break;

	//CHAT
	case "getChat":
		$ajaxContr->getChatAjaxAction();
		break;

	case "sendChat":
		$ajaxContr->sendChatAjaxAction();
		break;
	
	case "setActive":
		$ajaxContr->setUserActiveAjaxAction();
		break;
	
	case "getActive":
		$ajaxContr->getUserActiveAjaxAction();
		break;

	//GAME
	case "gameTick":
		$ajaxContr->gameTickAjaxAction();
		break;

	case "uncoverQuestion":
		$ajaxContr->uncoverAjaxAction();
		break;

	case "answerQuestion":
		$ajaxContr->answerQuestionAjaxAction();
		break;

	//ROOMS
	case "createRoom":
		$controller->createGameRoomAction();
		break;

	case "joinRoom":
		$controller->joinGameAction();
		break;

	case "leaveRoom":
		$controller->leaveGameRoomAction();
		break;

	case "reset":
		$db->dropAndCreate();
		break;
		
	case "luisDebug":
		$db->debug();
		break;

	/**
	 * admin bereich
	 */

	case "adminPage":
		$controller->adminAction();
		break;

	case "adminAddQuestion":
		$controller->adminAddQuestionAction();
		break;

	case "adminDeleteQuestion":
		$controller->adminDeleteQuestionAction();
		break;

	case "adminCorrectQuestion":
		$controller->adminCorrectQuestionAction();
		break;

	case "adminAddAnswer":
		$controller->adminAddAnswerAction();
		break;

	case "debug":
		
		$controller->debugAction();
		break;

	default:
		$controller->indexAction();
		break;
}