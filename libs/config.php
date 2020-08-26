<?php
use pw2016\utils\System as System;
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('utils/class.System.php');
$config = System::singleton();
$config->set('carpetaTpl', 'tpl/');									//carpeta de las plantillas
$config->set('carpetaLogs', 'logs/');								//carpeta de los logs
$config->set('carpetaIncludes', 'includes/');						//carpeta de los includes
$config->set('basedirContenidos','contenidos/');					//carpeta para los contenidos generados por los usuarios
$config->set('skin','default');										//carpeta tpl que usa el proyecto

$config->set('path','/Users/llopez/Documents/webserver/FRAMEWORK/');
$config->set('baseHref','http://localhost:8888/FRAMEWORK/');

$config->set('_servidor_bd1', 'localhost');							//qaay028.ipss-online.org url mysql del servidor 1
$config->set('_database_bd1', '');								//bd del servidor 1
$config->set('_user_bd1', '');										//user mysql del servidor 1
$config->set('_password_bd1', '');									//passw del servidor 1

$config->set('_servidor_bd2', 'localhost');							//url mysql del servidor 2
$config->set('_database_bd2', 'bd');								//bd del servidor 2
$config->set('_user_bd2', '');										//user mysql del servidor 2
$config->set('_password_bd2', '');									//passw del servidor 1


//	Language
$config->set('langs', array('es','ca'));
if (isset($_GET['lang']) && !empty($_GET['lang'])){
	if (in_array($_GET['lang'], $config->get('langs'))){
		$_SESSION['lang'] = $_GET['lang'];

	}
}else if (!isset($_SESSION['lang'])){

	$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

$config->set('language',$_SESSION['lang']);


?>
