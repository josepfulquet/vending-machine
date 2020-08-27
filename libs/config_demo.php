<?php
use jfc\utils\System as System;
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (isset($_POST) && count($_POST) > 0){
		$_post;
		foreach ($_POST as $field => $value){
			$_post .= $field."=".$value."&";
		}
		$_found = strpos($_SERVER['REQUEST_URI'], "?");
		if ($_found === false){
			$redirect .= "?".$_post;
		}
		else{
			$redirect .= "&".$_post;
		}
	}
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $redirect);
		exit();
}

require_once('utils/class.System.php');
$config = System::singleton();
$config->set('carpetaTpl', 'tpl/');									//carpeta de las plantillas
$config->set('carpetaLogs', 'logs/');								//carpeta de los logs
$config->set('carpetaIncludes', 'includes/');						//carpeta de los includes
$config->set('basedirContenidos','contenidos/');					//carpeta para los contenidos generados por los usuarios
$config->set('skin','default');										//carpeta tpl que usa el proyecto

$config->set('path','/');
$config->SetbaseRef('http://whatever');

$config->set('_servidor_bd1', 'localhost');							//qaay028.ipss-online.org url mysql del servidor 1
$config->set('_database_bd1', '');								//bd del servidor 1
$config->set('_user_bd1', '');										//user mysql del servidor 1
$config->set('_password_bd1', '');									//passw del servidor 1

$config->set('_servidor_bd2', 'localhost');							//url mysql del servidor 2
$config->set('_database_bd2', 'bd');								//bd del servidor 2
$config->set('_user_bd2', '');										//user mysql del servidor 2
$config->set('_password_bd2', '');									//passw del servidor 1


//	Language
$config->set('langs', array('es','en'));
if (isset($_GET['lang']) && !empty($_GET['lang'])){
	if (in_array($_GET['lang'], $config->get('langs'))){
		$_SESSION['lang'] = $_GET['lang'];

	}
}else if (!isset($_SESSION['lang'])){

	$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

$config->set('language',$_SESSION['lang']);


?>
