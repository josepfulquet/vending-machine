<?php
namespace jfc\utils;


/****************************************************
 * CLASS SecurityInvertred
***************************************************/

class Security{
	private static $instance;
	private $_system;


	function __construct(){
	}


	/**
	 * Method: sanitize
	 * is designed to receive an array (_POST, _GET, _SESSION ...)
	 * and tries to clean up all the fields using private method _cleanInput
	 * @param  array $input [_REQUEST]
	 * @return array $output [sanitized _REQUEST]
	 */
	public static function sanitize($input) {
		$output = array();
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = self::sanitize($val);
			}
		}
		else {
			/*
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}*/
			$output  = self::_cleanInput($input);
			//$output = mysql_real_escape_string($input);
		}
		return $output;
	}

	/**
	 * Method: _cleanInput
	 * removes javascript, html tags, CSS tags and multiline comments
	 * @param  string $input
	 * @return string $output [clean input]
	 */
	private static function _cleanInput($input) {
		$search = array(
			'@<script[^>]*?>.*?</script>@si', // javascript
			'@<[\/\!]*?[^<>]*?>@si',          // html tags
			'@<style[^>]*?>.*?</style>@siU',  // css tags
			'@<![\s\S]*?--[ \t\n\r]*>@'       // multiline comments
		);

		$output = preg_replace($search, '', $input);
		return $output;
	}

	/***********************************************************************************
	************************************************************************************
	***********************	              BLACKLIST        *****************************
	************************************************************************************
	***********************************************************************************/

	/***
		bannedIP
			checks if an IP address is the blacklist

		@param $ip (string)
		@return boolean

	***/

	public static function bannedIP($ip){
		/*$system 		= System::singleton();
		$file 			= $system->get('BLACKLIST_FILE');
		$bannedIps 	= json_decode(file_get_contents($file),true);
		if(in_array($ip, $bannedIps)){
			return true;
		}else{
			return false;
		}*/
		return false;
	}


	/***
		AddIPtoBlacklist
			adds an IP address to the blacklist file

		@param $ip (string)
		@return boolean

	***/

	public static function AddIPtoBlacklist($ip){
		$system 		= empanda\utils\System::singleton();
		$file 			= $system->get('ip_blacklist_file');
		$bannedIps 	= json_decode(file_get_contents($file),true);
		if(count($bannedIps)=== 0){
			$bannedIps = array();
		}
		if(!in_array($ip, $bannedIps)){
			array_push($bannedIps, $ip);
			file_put_contents($file,json_encode($bannedIps));
			return true;
		}else{
			return false;
		}
	}

	/***********************************************************************************
	************************************************************************************
	***********************	          END  BLACKLIST       *****************************
	************************************************************************************
	***********************************************************************************/

	/***********************************************************************************
	************************************************************************************
	***********************	                 TOR           *****************************
	************************************************************************************
	***********************************************************************************/

	/***
			IsTorExitPoint
				detecting malicius connections from TOR network

			@return BOOL

	***/

	public static function IsTorExitPoint(){
		/*function ReverseIPOctets($inputip){
			$ipoc = explode(".",$inputip);
			return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
		}
		if (gethostbyname(ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".".ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
			return true;
		} else {
			return false;
		}*/
			return false;
	}

	/***********************************************************************************
	************************************************************************************
	***********************	                 TOR           *****************************
	************************************************************************************
	***********************************************************************************/

	/***********************************************************************************
	************************************************************************************
	***********************	             acceptIP          *****************************
	************************************************************************************
	***********************************************************************************/

	/***
		acceptIP
			checks if an IP address can be accepted (nor tor nor banned)

		@param $ip (string)
		@return false or JSON

	***/

	public static function acceptIP($ip){
		if(self::IsTorExitPoint()){
			return array(
				'status'		=> 'Failed',
				'message'		=> 'Security Error',
				"description"	=> "IsTorExitPoint true",
				'code'			=> 403
			);
		}
		if(self::bannedIP($ip)){
			return array(
				'status'		=> 'Failed',
				'message'		=> 'Security Error',
				"description"	=> "Banned IP",
				'code'			=> 403
			);
		}
		return false;
	}

	/***********************************************************************************
	************************************************************************************
	***********************	         END acceptIP          *****************************
	************************************************************************************
	***********************************************************************************/

	/***********************************************************************************
	************************************************************************************
	***********************	                HELPERS        *****************************
	************************************************************************************
	***********************************************************************************/

	private function _logToBigbrother($logdata){
	//	Logbigbrother::writeErrorLog($logdata,"olecams_system_errors",true);
	}

	/***********************************************************************************
	************************************************************************************
	***********************	             END HELPERS        ****************************
	************************************************************************************
	***********************************************************************************/

	/************************************************************************************
																			Singleton
	*************************************************************************************/
	public static function singleton(){
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

}
?>
