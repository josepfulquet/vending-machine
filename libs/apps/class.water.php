<?php
include_once('libs/apps/class.product.php');

class Water extends Product{
	
	public function __construct(){
		$this->_price = 0.65;
	}
}	
?>