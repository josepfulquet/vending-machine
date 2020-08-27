<?php
header('Content-Type: application/json');

use jfc\utils\System as System;
use jfc\utils\Security as Security;

class Controller{
	
	private $_system;
	
	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		
		$request = $this->_system->getRequest();
		$request->request->replace(Security::sanitize($request->request->all()));
		
		$itemsWater = $request->request->get('water');
		$itemsJuice = $request->request->get('juice');
		$itemsSoda = $request->request->get('soda');
		$coin005 = $request->request->get('coin005');
		$coin010 = $request->request->get('coin010');
		$coin025 = $request->request->get('coin025');

		
		
		
		require_once('libs/apps/class.water.php');
		$water = new Water();
		$water->setStock($itemsWater);
		
		require_once('libs/apps/class.juice.php');
		$juice = new Juice();
		$juice->setStock($itemsJuice);
		
		
		require_once('libs/apps/class.soda.php');
		$soda = new Soda();
		$soda->setStock($itemsSoda);
		
		require_once('libs/apps/class.machine.php');
		$machine = new Machine();
		$machine->setAvailableChange("coin005", $coin005);
		$machine->setAvailableChange("coin010", $coin010);
		$machine->setAvailableChange("coin025", $coin025);
		$response = array(
			"status"	=> "OK",
			"code"		=> 200,
			"message"	=> array(
							"water" => $water->getStock(), 
							"juice" => $juice->getStock(), 
							"soda" => $soda->getStock(), 
							"0,05" => $machine->getAvailableChange("coin005"), 
							"0,10" => $machine->getAvailableChange("coin010"), 
							"0,25" => $machine->getAvailableChange("coin025")
						)
		);
		
		
		
		echo json_encode($response);
		
		
		

	}

}

new Controller();
?>
