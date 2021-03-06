<?php
namespace jfc\utils;
require_once __DIR__ .'/../../includes/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DB as DB;


class System{
		private $vars;
		private static $instance;
		public $sesion;
		private $_queBD;
		private $_logger;
		private $_con;
		private $_request;

		private function __construct(){
			require_once 'class.db.php';
			require_once 'class.security.php';
			require_once 'class.check.php';

			$this->vars = array();
			session_start();
			$this->sesion=$_SESSION;
			$this->_queBD="bd1";
			//SYMPHNOY HttpFoundation
			$this->_request 	= Request::createFromGlobals();
		}

		/************************************************************************************
		*************************************************************************************
		*********************           SYMPHONY HttpFoundation         *********************
		*************************************************************************************
		*************************************************************************************/

		public function sendResponse($content,$type='text/html',$statusCode=200){
			if($type==='application/json'){
				$response = new JsonResponse(
					$content,
					$statusCode
				);
			}else{
				$response = new Response(
					$content,
					$statusCode
				);
				$response->headers->set('Content-Type', $type);
			}

			$response->send();
		}

		public function getRequest(){
			return $this->_request;
		}
		
		public function setResponse($message,$code,$status="Accepted"){
			return array(
				"status"	=> $status,
				"message"	=> $message,
				"code"		=> (int)$code
			);
		}


		/************************************************************************************
		*************************************************************************************
		*********************           END SYMPHONY HttpFoundation      ********************
		*************************************************************************************
		*************************************************************************************/

		

		/************************************************************************************
		*************************************************************************************
		*********************          config.php SETTER/GETTER         *********************
		*************************************************************************************
		*************************************************************************************/

		public function set($name, $value){
			if(!isset($this->vars[$name])){
				$this->vars[$name] = $value;
			}
		}

		public function get($name){
			if(isset($this->vars[$name])){
				return $this->vars[$name];
			}
		}

		/************************************************************************************
		*************************************************************************************
		*********************       END config.php SETTER/GETTER        *********************
		*************************************************************************************
		*************************************************************************************/

		/************************************************************************************
		*************************************************************************************
		**************************                 MYSQL              ***********************
		*************************************************************************************
		*************************************************************************************/

		public function getBD(){
			if($this->_queBD=="bd1"){
				return 1;
			}elseif($this->_queBD=="bd2"){
				return 2;
			}elseif($this->_queBD=="bd3"){
				return 3;
			}
		}

		private function _setBD($cual){
			if($cual=="bd1"){
				$this->_queBD="bd1";
			}elseif($cual=="bd2"){
				$this->_queBD="bd2";
			}elseif($cual=="bd3"){
				$this->_queBD="bd3";
			}else{
				$this->_queBD="bd1";
			}
		}

		//Fin registro bd del proyecto
		function pdo($queDB){
			$this->_setBD($queDB);
			return DB::pdo($this->getBD());
		}

		function pdo_select($queDB,$sql, $npage=null, $nrow=null){
			$this->_setBD($queDB);
			$db = DB::pdo($this->getBD());
			$db->prepare_select($sql);
			$rs = $db->select();
			return ($rs);
		}

		function pdo_insert($queDB,$table, $fields, $values){
			$this->_setBD($queDB);
			$db = DB::pdo($this->getBD());
			$db->prepare_insert($table, $fields);
			$last_id = $db->insert($values);
			return ($last_id);
		}

		function pdo_update($queDB,$table, $fields, $values, $id=null, $where=null){
			$this->_setBD($queDB);
			$db = DB::pdo($this->getBD());
			$db->prepare_update($table, $fields, $id, $where);
			$db->update($values);
		}

		function pdo_delete($queDB,$table, $ids=null, $where=null){
			$this->_setBD($queDB);
			$db = DB::pdo($this->getBD());
			$db->prepare_delete($table, $ids, $where);
			$db->delete();
		}

		function pdo_count()	{
			$db = DB::pdo($this->getBD());
			return ($db->count);
		}

		/************************************************************************************
		*************************************************************************************
		**************************             END MYSQL              ***********************
		*************************************************************************************
		*************************************************************************************/


		/************************************************************************************
		*************************************************************************************
		*********************                     HELPERS               *********************
		*************************************************************************************
		*************************************************************************************/

		public function debug($content){
			echo "<pre>";
				print_r($content);
			echo "</pre>";
		}

		/************************************************************************************
		*************************************************************************************
		*********************                   END  HELPERS            *********************
		*************************************************************************************
		*************************************************************************************/


		/************************************************************************************
										System Singleton
		*************************************************************************************/
		public static function singleton(){
			if (!isset(self::$instance)) {
				$c = __CLASS__;
				self::$instance = new $c;
			}
			return self::$instance;
		}


}

?>
