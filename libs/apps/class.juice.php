<?php
include_once('libs/apps/class.product.php');
	
class Juice extends Product{
	
	public function __construct(){
		$this->_price = 1.00;
	}
}	
?>