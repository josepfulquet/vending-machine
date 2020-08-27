<?php
class Product{
	protected $_price;
	protected $_stock;
	
	public function getPrice(){
		return $this->_price;
	}
	
	public function getStock(){	
		return $this->_stock;	
	}
	
	public function setPrice($price){
		$this->_price = $price;
	}
	
	public function setStock($stock){
		$this->_stock = $stock;
	}
	
	public function isAvailable(){
		return $this->_stock > 0 ? true : false;
	}
	
	public function enoughCoins($amount){
		if ($amount >= $this->_getPrice()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function vendItem(){
		$this->setStock($this->getStock() - 1);
	}
	
}
?>