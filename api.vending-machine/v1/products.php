<?php
use jfc\utils\System as System;
use jfc\utils\Security as Security;
use jfc\apps\Products as Products;

class Controller{
	
	private $_system;

	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		
		$request				= $this->_system->getRequest();
		$data 					= json_decode($request->getContent(), true); //HttpFoundation equivalent for $_POST = json_decode(file_get_contents('php://input'), true)
		$request->request->replace(Security::sanitize($data));

		$method					= $request->request->get('method');
		$water					= $request->request->get('water');
		$juice					= $request->request->get('juice');
		$soda					= $request->request->get('soda');

		if ($method === null){
			$res = array("status"=>"Failed", "code"=>404, "message"=>"Method not found");
		}
		else{
			include_once('libs/apps/class.products.php');
			$products		= new Products();
			
			if ($method === "get"){	
				$res = $products->getProducts();
			}
			else if ($method === "set"){
				$res = $products->setProducts($water, $juice, $soda);
			}

		}

		$this->_system->sendResponse($res,'application/json');

	}

}

new Controller();
?>
