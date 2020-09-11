<?php
use jfc\utils\System as System;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('utils/class.System.php');
$config = System::singleton();
$config->set('carpetaIncludes', 'includes/');						//carpeta de los includes

$config->set('path','');
$config->set('baseHref','http://localhost/api.vending-machine/');
$config->set('environment','dev');

$config->set('_servidor_bd1', 'localhost');							//qaay028.ipss-online.org url mysql del servidor 1
$config->set('_database_bd1', 'vendingmachine');					//bd del servidor 1
$config->set('_user_bd1', 'root');									//user mysql del servidor 1
$config->set('_password_bd1', 'root');								//passw del servidor 1

?>
