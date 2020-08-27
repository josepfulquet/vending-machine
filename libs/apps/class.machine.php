<?php
class Machine{
	
	private $_availableCoins;
	private $_availableChange;
	
	private $_currentVend;
	
	public function __construct(){
		$this->_availableCoins = array("coin005", "coin010", "coin025", "coin1");
		$this->_availableChange = array();
	}	
	
	public function getAvailableChange($coin = null){
		if ($coin === null){
			return $this->_availableChange;
		}
		else{
			if (in_array($coin, $this->_availableCoins, true)){
				return $this->_availableChange[$coin];
			}
			else{
				return false;
			}
		}
	}
	
	public function setAvailableChange($coin, $quantity){
		if (in_array($coin, $this->_availableCoins, true)){
			$this->_availableChange[$coin] = $quantity;		
		}
		else{
			return false;
		}
	}
	
	public function insertCoin($coin){
		$this->setAvailableChange($coin, 1);
		$this->_currentVend += $coin;
	}
}
?>