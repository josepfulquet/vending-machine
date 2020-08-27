<?php
namespace jfc\utils;
class Crypt{

	function __construct(){

	}

	public function encryptData($value){
		return base64_encode(openssl_encrypt($value, 'aes128', CRYPTKEY,true,"1234567812345678"));
	}

	public function decryptData($value){
		$crypttext = base64_decode($value);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, CRYPTKEY, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}

	/**
	 * decodeQuerystring function.
	 *
	 * @access public
	 * @param string $string
	 * @param boolean $encoded
	 * @return array
	 */
	public function decodeQuerystring($string, $encoded){
		$array = array();
		if ($encoded){
			$string = base64_decode($string);
		}
	  	$a_1 = explode('&', $string);
	  	foreach ($a_1 as $item){
		  	$a_2 = explode('=', $item);
		  	$array[$a_2[0]] = $a_2[1];
	  	}

	  	return $array;
	}

	/**
	 * encodeQuerystring function.
	 *
	 * @access public
	 * @param array $array
	 * @return string
	 */
	public function encodeQuerystring($array){
		$pos = 0;
		$str = "";
		foreach ($array as $field => $value){
			$str .= ($pos==0) ? $field.'='.$value : '&'.$field.'='.$value;
			//$str = $strTmp;
			$pos++;
		}
		$str .= '&cache='.substr(sha1(rand()), 0, 25);

		return base64_encode($str);
	}
}
?>
