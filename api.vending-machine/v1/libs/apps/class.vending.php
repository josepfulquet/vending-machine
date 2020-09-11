<?php
namespace jfc\apps;

use jfc\utils\System as System;
use jfc\apps\Products as Products;
use jfc\apps\Change as Change;

class Vending{
	
	protected $_system;
	
	private $_products;
	private $_change;
	
	private $_availableCoins;
	private $_availableItems;
	
	public function __construct(){
		
		require_once ('libs/config.php');
		$this->_system	= System::singleton();
		
		include_once('libs/apps/class.products.php');
		$this->_products = new Products();
		include_once('libs/apps/class.change.php');
		$this->_change = new Change();
		
		$this->_availableCoins = array('005','010','025','100');
		$this->_availableItems = array('water', 'juice', 'soda');
	}	

	

	public function insertCoin($coin){
		
		try{
			
			if (in_array($coin, $this->_availableCoins)){
				
				$coinsNewValue = 1;
				$coinsCount = $this->_change->getChange($coin);
				if ($coinsCount["status"] === "Accepted"){		
					$coinsNewValue = 1 + (int)$coinsCount["message"][0]["count"];
				}
				$this->_change->updateCoins($coin, $coinsNewValue);
												
												
				$currentNewValue = 1;
				$currentCount = $this->_change->getCurrent($coin);
				if ($currentCount["status"] === "Accepted"){
					$currentNewValue = 1 + (int)$currentCount["message"][0]["count"];
				}	
				$this->_change->updateCurrent($coin, $currentNewValue);
						
											
				$responseData = array();
				
				$allCurrent = $this->_change->getCurrent();
				if ($allCurrent["status"] === "Accepted"){
					$responseData["current"] = $allCurrent["message"];
				}
				
				$allChange = $this->_change->getChange();
				if ($allChange["status"] === "Accepted"){
					$responseData["change"] = $allChange["message"];
				}
				
				$response = $this->_system->setResponse($responseData, 200, "Accepted");
			
			}
			else{
				$response = $this->_system->setResponse('coin not accepted', 404, 'Failed');
			}
			
			return $response;
											
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}
	}
	
	public function returnInsertedCoins(){
		
		try{
			$current = $this->_change->getCurrent();
			$change = $this->_change->getChange();
			
			$returnCoins = array();
			
			if ($current["status"] === "Accepted"){
				
	
				$currentCoins = $current["message"];
				foreach ($currentCoins as $item){
					$returnCoins[$item["coin"]] = $item["count"];
					$this->_change->updateCurrent($item["coin"], 0);
				}
				
				$walletChange = array();
				foreach ($change["message"] as $item){
					//$walletChange[$item["coin"]] = (int)$item["count"] - (int)$returnCoins[$item["coin"]];
					$this->_change->updateCoins($item["coin"], (int)$item["count"] - (int)$returnCoins[$item["coin"]]);
				}
				
				$newChange = $this->_change->getChange();
				
				$return = $this->_system->setResponse(array("returned"=>$currentCoins, "change"=>$newChange["message"]), 200, 'Accepted');

			}
			else{
				$return = $this->_system->setResponse("No coins to return", 404, 'Failed');
			}
			return $return;
			
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}

		
	}
	
	public function buyItem($item){
		try{
			if (!empty($item)){
				$product = $this->_products->getProducts($item);
				if ($product["status"] === "Accepted"){
					$count = $product["message"][0]["stock"];
					$price = $product["message"][0]["price"];
					if ($count > 0){
						$currentAmount = $this->_change->getCurrentAmount();
						if ($currentAmount == $price){
							$response = $this->_makeThePurchase($item, $count, $price, $currentAmount);
						}
						else if ($currentAmount > $price){
							if ($this->_change->isChangeAvailable() === true){
								$response = $this->_makeThePurchase($item, $count, $price, $currentAmount);
							}
							else{
								$response = $this->_system->setResponse('just exact amount', 404, 'Failed');
							}
						}
						else{
							$response = $this->_system->setResponse('not enought money', 404, 'Failed');
						}					
					}
					else{
						$response = $this->_system->setResponse('item not available', 404, 'Failed');
					}
				}
				else{
					$response = $product;
				}
			}
			else{
				$response = $this->_system->setResponse('empty item', 404, 'Failed');
			}
			
			return $response;
			
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}
	}
	
	
	private function _makeThePurchase($item, $count, $price, $currentAmount){
		$newStock = $count - 1;						
		$this->_products->updateStock($item, $newStock);
		$amountToReturn = $currentAmount - $price;
								
		$this->_change->clearCurrent();
								
		$responseData = array();
								
		$responseData["returned"] = $this->_change->returnChange($amountToReturn);
		$responseData["purchased"] = $item;
								
		$allCurrent = $this->_change->getCurrent();
		if ($allCurrent["status"] === "Accepted"){
			$responseData["current"] = $allCurrent["message"];
		}
								
		$allChange = $this->_change->getChange();
		if ($allChange["status"] === "Accepted"){
			$responseData["change"] = $allChange["message"];
		}
								
		$allProducts = $this->_products->getProducts();
		if ($allProducts["status"] === "Accepted"){
			$responseData["products"] = $allProducts["message"];
		}
								
		return $this->_system->setResponse($responseData, 200, "Accepted");
	}
	
}
?>