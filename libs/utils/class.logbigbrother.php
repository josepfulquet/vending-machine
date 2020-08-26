<?php
namespace olecams\utils;
class bigbrother {
	public static function writeErrorLog($datos,$bucket,$monthly=null){
		if($monthly){
			$month        	= date('m');
			$year        		= date('Y');
			$bucket					= strtoupper($bucket."_".$month."_".$year);
		}
		$datos["bucket" ]			= $bucket;
		$json_val 						= json_encode($datos);
		$url 									= "http://bigbrother.invertred.com/webhook.log.php";
		$ch 									= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".BIG_BROTHER_KEY, "Content-type: application/json"));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HEADER,false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_val);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLINFO_HEADER_OUT, false);
		$html = curl_exec($ch);
		$json = json_decode($html, true);

		if(count($json)>0){
			$retorno = $json;
		}else{
			$retorno = array("status"=>"Failed","message"=>curl_error($ch));
		}
		curl_close($ch);
		return $retorno;
	}
}

?>
