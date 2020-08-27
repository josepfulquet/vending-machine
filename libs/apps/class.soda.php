<?php
include_once('libs/apps/class.product.php');

class Soda extends Product{
	
	public function __construct(){
		$this->_price = 1.50;
	}
}	
?>