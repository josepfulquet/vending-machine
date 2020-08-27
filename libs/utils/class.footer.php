<?php
namespace jfc\utils;
use jfc\utils\System as System;
use jfc\utils\Check as Check;
class Footer{
	private $_system;
	function __construct(){
		$this->_system = System::singleton();
	}

	public function renderFooter($options=null){
		$data["baseHref"]			= $this->_system->get('baseHref');
		$data["skin"]				= $this->_system->get('skin');
		$data['env']				= $this->_system->get('environment');
		$this->_system->fShow($this->_system->get('skin')."/footer.php",$data);
	}
}
?>
