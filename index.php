<?php
use pw2016\utils\System as System;
use pw2016\utils\Security as Security;
class ControllerIndex{
	private $_system;
	function __construct(){

		require_once 'libs/config.php';
		$this->_system = System::singleton();
		$this->_request	= $this->_system->getRequest();
		$this->_request->request->replace(Security::sanitize($this->_request->query->all()));
		$email = $this->_request->query->get('user');
		$data["baseHref"] = $this->_system->get('baseHref');
		$data['env'] = $this->_system->get('env');
    echo "lo veo";

	}

}

new ControllerIndex();

?>
