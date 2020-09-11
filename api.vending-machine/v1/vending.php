<?php
use jfc\utils\System as System;
use jfc\utils\Security as Security;
use jfc\apps\Vending as Vending;

class Controller{
	
	private $_system;

	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		
		$request				= $this->_system->getRequest();
		$data 					= json_decode($request->getContent(), true); //HttpFoundation equivalent for $_POST = json_decode(file_get_contents('php://input'), true)
		$request->request->replace(Security::sanitize($data));

		$method					= $request->request->get('method');
		$coin					= $request->request->get('coin');
		$item					= $request->request->get('item');

		if ($method === null){
			$res = array("status"=>"Failed", "code"=>404, "message"=>"Method not found");
		}
		else{
			include_once('libs/apps/class.vending.php');
			$vending		= new Vending();
			
			if ($method === "insertcoin"){
				$res = $vending->insertCoin($coin);				
			}
			else if ($method === "returncoins"){
				$res = $vending->returnInsertedCoins();				
			}
			else if ($method === "buy"){
				$res = $vending->buyItem($item);
			}
		}

		$this->_system->sendResponse($res,'application/json');

	}

}

new Controller();
?>
