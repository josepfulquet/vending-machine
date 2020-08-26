<?php
namespace pw2016\utils;
class Check{
	public function __construct($needed=true){
		if ($needed){
			if (!isset($_SESSION['logged']) || !$_SESSION['logged']){
				header('Location:index.php');
				exit();
			}
		}
	}
}
?>
