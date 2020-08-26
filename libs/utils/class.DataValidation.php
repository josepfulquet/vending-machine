<?php
/****************************************************
 * CLASS DataValidation
 * Eneror 2013 Leandro Lopez Guerrero (leandro.lopez@3data.net)
  
 * Valida datos (strings, números, etc)
  
***************************************************/
class DataValidation{


	/*
	isString checks if is a string
	@params $value - string
	2returns bool
	*/

	public static function isString($value){
		return is_string($value);
	}
	
	/*
	isIsoCountry checks if is an ISO country cody (string, length )
	@params $value - string
	@returns bool
	*/

	public static function isIsoCountry($value){
		if(is_string($value)){
			if(strlen($value)===2){
				if (preg_match("/[A-Za-z]{2}$/",$value, $matches)) {
					return true;
				}else{
					return false;
				}	
			}else{
				return false;
			}
		}else{
			return false;
		}		
			
	}
	
	
	public static function ValidateDate($date) {
	
		//match the format of the date
		if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{2})$/", $date, $parts)){
			//check weather the date is valid of not
			if(checkdate($parts[2],$parts[3],$parts[1])){
				return true;
			
			}else{
				return false;
			}
		}else{
			return false;	
		}	
	}
	
	public static function ValidateDateInterval($first,$second){
		$today = strtotime($first);
		$expiration_date = strtotime($second);
		
		if ($expiration_date > $today || $expiration_date == $today) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function ValidateHour($hour){
		if(strlen($hour)!=5){
			return false;
		}else{
			if(preg_match("/^([0-9]{2}):([0-9]{2})$/", $hour,$parts)){
				if($parts[1]<24 && $parts[2]<60){
					return true;
				}else{
					return false;
				}			
			}else{
				return false;
			}
			
		}		
	}
	public static function ValidateMonth($month){
		if(preg_match("/^([0-2]{4})-([0-9]{2})$/", $month,$parts)){
				if(checkdate($parts[2],"01",$parts[1])){
					return true;
				}else{
					return false;
				}			
			}else{
				return false;
			}

		
	}
}
?>