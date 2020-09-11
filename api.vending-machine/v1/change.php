<?php
use jfc\utils\System as System;
use jfc\utils\Security as Security;
use jfc\apps\Change as Change;

class Controller{
	
	private $_system;

	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		
		$request				= $this->_system->getRequest();
		$data 					= json_decode($request->getContent(), true); //HttpFoundation equivalent for $_POST = json_decode(file_get_contents('php://input'), true)
		$request->request->replace(Security::sanitize($data));

		$method					= $request->request->get('method');
		$coin005				= $request->request->get('coin005');
		$coin010				= $request->request->get('coin010');
		$coin025				= $request->request->get('coin025');

		if ($method === null){
			$res = array("status"=>"Failed", "code"=>404, "message"=>"Method not found");
		}
		else{
			include_once('libs/apps/class.change.php');
			$change		= new Change();
			
			if ($method === "get"){			
				$res = $change->getChange();
			}
			else if ($method === "getcurrent"){			
				$res = $change->getCurrent();
			}
			else if ($method === "set"){
				$res = $change->setChange($coin005, $coin010, $coin025);
			}
			
			else if ($method === "collect"){
				$res = $change->collect();
			}

		}

		$this->_system->sendResponse($res,'application/json');

	}

}

new Controller();
?>
