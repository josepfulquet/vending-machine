<?php
use jfc\utils\System as System;
use jfc\utils\Security as Security;
use jfc\utils\Header as Header;
use jfc\utils\Footer as Footer;
class Controller{
	
	private $_system;
	
	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		
		$this->_request	= $this->_system->getRequest();
		$this->_request->request->replace(Security::sanitize($this->_request->query->all()));
		$email = $this->_request->query->get('user');
		
		$data["baseHref"]	= $this->_system->get('baseHref');
		$data['env']		= $this->_system->get('env');
		$data["lang"]		= $this->_system->get("lang");
		
		$header = new Header();
		$header->renderHeader();
		
		
		require_once('libs/apps/class.soda.php');
		$soda = new Soda();
		$data["soda"] = $soda->getStock();
		
		require_once('libs/apps/class.juice.php');
		$juice = new Juice();
		$data["juice"] = $juice->getStock();
		
		require_once('libs/apps/class.water.php');
		$water = new Water();
		$data["water"] = $water->getStock();
		
		
		$this->_system->fShow($this->_system->get('skin')."/tpl_service.php", $data);
		
		
		$footer = new Footer();
		$footer->renderFooter();

	}

}

new Controller();
?>
