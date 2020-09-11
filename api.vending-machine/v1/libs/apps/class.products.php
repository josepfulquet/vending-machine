<?php
namespace jfc\apps;

use jfc\utils\System as System;

class Products{
	
	protected $_system;


	public function __construct(){
		require_once ('libs/config.php');
		$this->_system	= System::singleton();
	}
		
	public function getProducts($product=null){
		
		try{
			$query = "SELECT * FROM products";
			if ($product !== null){
				$query .= " WHERE product='$product'";
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
	                    "product"					=> $row["product"],
	                    "stock"                     => $row["stock"],
	                    "price"						=> $row["price"]
	                );
	                array_push($data, $item);
	            }
	        }
	        else{
	            $status = "Failed";
	            $code = 404;
	        }
			return array(
						'code'		=> $code,
						'message'	=> $data, 
						'status'	=> $status
						);
			
		}
		catch (Exception $e){
			return $this->_system->setResponse($e, 500, 'Failed');		}
	}
	
	public function setProducts($water, $juice, $soda){
		
		$error 			= array();
		$response		= array();

		try{
			if (empty($water) || !is_numeric($water)){
				array_push($error, 'Bad value for Water');
			}
			if (empty($juice) || !is_numeric($juice)){
				array_push($error, 'Bad value for Juice');
			}
			if (empty($soda) || !is_numeric($soda)){
				array_push($error, 'Bad value for Soda');
			}
			
			if (count($error) === 0){
				
				$this->updateStock('water', (int)$water);
				
				$this->updateStock('juice', (int)$juice);
				
				$this->updateStock('soda', (int)$soda);
											
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
	
	
	public function updateStock($product, $quantity){
		$this->_system->pdo_update("bd1", "products",  "stock", array($quantity), null, "product='$product'");
	}
	
}
?>