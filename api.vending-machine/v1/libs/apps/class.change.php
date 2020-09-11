<?php
namespace jfc\apps;

use jfc\utils\System as System;

class Change{
	
	protected $_system;


	public function __construct(){
		require_once ('libs/config.php');
		$this->_system	= System::singleton();
	}
	
	public function getChange($coin=null){
		
		try{
			$query = "SELECT * FROM coins";
			if ($coin !== null){
				$query .= " WHERE coin='$coin'";
			}

			$rs = $this->_system->pdo_select("bd1", $query);

			$data = array();
			$status = "";
			
			if (count($rs) > 0){
	            $status = "Accepted";
	            $code = 200;
	
	            foreach ($rs as $row){	
	
	                $item = array(
		                "id"						=> $row["id"],
	                    "coin"						=> $row["coin"],
	                    "value"                     => $row["value"],
	                    "count"						=> $row["count"]
	                );
	                array_push($data, $item);
	            }
	        }
	        else{
	            $status = "Failed";
	            $code = 404;
	        }
	        return $this->_system->setResponse($data, $code, $status);

			
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}
	}
	
	public function isChangeAvailable(){
		$available = true;
		$change = $this->getChange();
		if ($change["status"] === "Accepted"){
			foreach ($change["message"] as $item){
				if ($item['coin'] === "005"){
					if ($item['count'] < 1){
						$available = false;
						break;
					}
				}
				else if ($item['coin'] === "010"){
					if ($item['count'] < 1){
						$available = false;
						break;
					}
				}
				else if ($item['coin'] === "025"){
					if ($item['count'] < 2){
						$available = false;
						break;
					}
				}
			}
		}
		else{
			$available = false;
		}
		return $available;
		
	}
	
	public function getCurrent($coin = null){
		
		try{
			$query = "SELECT * FROM current";
			if ($coin !== null){
				$query .= " WHERE coin='$coin'";
			}

			$rs = $this->_system->pdo_select("bd1", $query);

			$data = array();
			$status = "";
			if (count($rs) > 0){
	            $status = "Accepted";
	            $code = 200;
	
	            foreach ($rs as $row){
	
	
	                $item = array(
		                "id"						=> $row["id"],
	                    "coin"						=> $row["coin"],
	                    "value"                     => $row["value"],
	                    "count"						=> $row["count"]
	                );
	                array_push($data, $item);
	            }
	        }
	        else{
	            $status = "Failed";
	            $code = 404;
	        }
	        
			return $this->_system->setResponse($data, $code, $status);
			
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}
	}
	
	public function getCurrentAmount(){
		$currentAmount = 0;
		$current = $this->getCurrent();
		
		if ($current["status"] === "Accepted"){
			foreach ($current["message"] as $coin){
				$currentAmount += ((float)$coin["value"] * (int)$coin["count"]);
			}
		}
		
		return $currentAmount;
	}
	
	public function setChange($coin005, $coin010, $coin025){
		
		$error 			= array();
		$response		= array();

		try{
			if (empty($coin005) || !is_numeric($coin005)){
				array_push($error, 'Bad value for coin 005');
			}
			if (empty($coin010) || !is_numeric($coin010)){
				array_push($error, 'Bad value for coin 010');
			}
			if (empty($coin025) || !is_numeric($coin025)){
				array_push($error, 'Bad value for coin 025');
			}
			
			if (count($error) === 0){
				$this->updateCoins('005', (int)$coin005);
				$this->updateCoins('010', (int)$coin010);
				$this->updateCoins('025', (int)$coin025);
				$this->updateCoins('100', 0);

											
				$response = $this->_system->setResponse("OK",200,'Accepted');
			}
			else{
				$response = $this->_system->setResponse("Bad Data",406,'Failed');
			}
			
			return $response;
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');
		}
	}
	

	
	
	public function returnChange($amount){
		if ($amount > 0){
			if ($amount % 5 === 0){
				$amount = number_format($amount, 2);
				$returnedCoins = array();
				while ($amount >= 1){
					if (!isset($returnedCoins['100'])){
						$returnedCoins['100'] = 0;
					}
					$returnedCoins['100'] += 1;
					$amount -= 1;
				}
				$amount = number_format($amount, 2);
				while ($amount >= 0.25){
					if (!isset($returnedCoins['025'])){
						$returnedCoins['025'] = 0;
					}
					$returnedCoins['025'] += 1;
					$amount -= 0.25;
				}
				$amount = number_format($amount, 2);
				while ($amount >= 0.10){
					if (!isset($returnedCoins['010'])){
						$returnedCoins['010'] = 0;
					}
					$returnedCoins['010'] += 1;
					$amount -= 0.10;
				}
				$amount = number_format($amount, 2);
				while ($amount >= 0.05){
					if (!isset($returnedCoins['005'])){
						$returnedCoins['005'] = 0;
					}
					$returnedCoins['005'] += 1;
					$amount -= 0.05;
				}
				$amount = number_format($amount, 2);
				
				$result = array();
				foreach ($returnedCoins as $key => $value){
					$change = $this->getChange($key);
					if ($change["status"] === "Accepted"){
						$coins = $change["message"][0]["count"];
						$this->updateCoins($key, ($coins-$value));
						array_push($result, 
									array(
										'coin' => $key,
										'value' => (float)$key/100,
										'count' => $value
									));
					}
					
				}
				
				return $result;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
		
	}
	
	public function clearCurrent(){
		$this->updateCurrent('005', 0);
		$this->updateCurrent('010', 0);
		$this->updateCurrent('025', 0);
		$this->updateCurrent('100', 0);
	}
	
	
	public function updateCoins($coin, $count){
		$this->_system->pdo_update("bd1", 
											"coins",
											"count",
											array($count),
											null,
											"coin='$coin'");
	}
	
	
	public function updateCurrent($coin, $count){
		
		$this->_system->pdo_update("bd1", 
											"current",
											"count",
											array($count),
											null,
											"coin='$coin'");
		
	}	
}
?>