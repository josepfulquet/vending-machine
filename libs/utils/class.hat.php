<?php
namespace pw2016\utils;
use pw2016\utils\System as System;
use pw2016\utils\Check as Check;
class Hat{
	private $_system;
	function __construct(){
		$this->_system = System::singleton();

	}

	public function renderHeader($pagina=null){
		$data["baseHref"]		= $this->_system->GetBaseRef();;
		$data["skin"]				= $this->_system->get('skin');
		$data['env']				= $this->_system->getEnviroment();
		//$this->_system->fShow($this->_system->get('skin')."/tpl_header.php",$data);
	}
}
?>
