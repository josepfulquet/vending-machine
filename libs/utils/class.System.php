<?php
namespace pw2016\utils;
require_once __DIR__ .'/../../includes/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DB as DB;
use pw2016\utils\Visor as Visor;
use pw2016\utils\RedisInvertred as RedisInvertred;
use pw2016\utils\MongoPW2016 as MongoPW2016;

class System{
		private $vars;
		private static $instance;
		public $sesion;
		private $_queBD;
		private $_logger;
		private $_con;
		private $_hat;
		private $_request;

		private function __construct(){
			require_once 'class.db.php';
			require_once 'class.visor.php';
			require_once 'class.hat.php';
			require_once 'class.shoe.php';
			require_once 'class.security.php';
			require_once 'class.check.php';
			require_once 'class.logbigbrother.php';
			require_once 'class.redis.invertred.php';
			require_once 'class.mongodb.php';
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


		/************************************************************************************
		*************************************************************************************
		*********************           END SYMPHONY HttpFoundation      ********************
		*************************************************************************************
		*************************************************************************************/

		/************************************************************************************
		*************************************************************************************
		*********************               TEMPLATES RENDERER          *********************
		*************************************************************************************
		*************************************************************************************/

		public function loadConstants(){
			if(file_exists(__DIR__ .'/../../includes/locales/'.$this->get('language').'/constants.php')){
				require_once __DIR__ .'/../../includes/locales/'.$this->get('language').'/constants.php';
			}else{
				require_once __DIR__ .'/../../includes/locales/es/constants.php';
			}
		}

		public function fShow($plantilla,$data){
			$this->sesion=$_SESSION;
			$this->visor=new Visor();
			$this->visor->fShow($plantilla,$data);
		}

		public function imprError($err){
			try{
				if(!class_exists('ErrorLogger')) {
					throw new Exception("clase ErrorLogger no existe");
				}else{
					$this->_logger=ErrorLogger::singleton();
					if($err==""){
						throw new Exception("Error está vacio");
					}else{
						$this->_logger->imprError($err);
					}
				}
			}catch (Exception $e) {
				switch ($e->getMessage()) {
					case "clase ErrorLogger no existe":
						//no hagas nada, no hay handler de errores
						//	echo "no hay clase";
						break;
					case "Error está vacio":
					//no hagas nada, no hay handler error
					 $this->_logger->imprError("Error está vacio");
					 break;
				}
			}
		}

		/************************************************************************************
		*************************************************************************************
		*********************              END TEMPLATES RENDERER       *********************
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
		**************************                 MONGO              ***********************
		*************************************************************************************
		*************************************************************************************/

		public function fConectaMongo($server=null){
			$this->_mongo 	= MongoPW2016::singleton();

			/*múltiple hosts mongo
			en el config se pueden poner todos los host mongo que queramos, con la siguiente nomenclatura
				_servidorMongoNUM
				_bdMongoNUM
				_usMomgoNUM
				_passwMongNUM

			Ejemplo:
				$config->set('_servidorMongo1','url');
				$config->set('_bdMongo1','nv');
				$config->set('_usMongo1','adgrup');
				$config->set('_passwMongo1','4ng3l43');
			*/
			if(!$server){
				$server=1;
			}

			$h				= "_servidorMongo".$server;
			$b				= "_bdMongo".$server;
			$u				= "_usMongo".$server;
			$p				= "_passwMongo".$server;
			$host			= $this->get($h);
			$bd				= $this->get($b);
			$us				= $this->get($u);
			$pw				= $this->get($p);

			$this->_mongo->setDataBD($host,$bd,$us,$pw);
			$driver=$this->_mongo->fConecta();
			return $driver;
		}

		public function fDesconectaMongo(){
			$this->_mongo->fDesconectaMongo();
		}

		public function fDameId($col){
			return $this->_mongo->fDameId($col);
		}

		public function insertMongo($bd,$table,$datos){
			$m		= $this->fConectaMongo($bd);
			$collection				= $m->selectCollection($table);
			$datos['fecha'] 	= new MongoDB\BSON\UTCDateTime();
			$insert 					= $collection->insertOne($datos);
			$id								= $insert->getInsertedId()->__toString();
			$this->fDesconectaMongo();
			return $id;
		}

		public function deleteMongo($bd,$table,$query){
			$m		= $this->fConectaMongo($bd);
			$collection				= $m->selectCollection($table);
			$result 					= $collection->deleteOne($query);
			$this->fDesconectaMongo();
			return $result;
		}

		public function queryMongo($bd,$table,$query){
			$m		= $this->fConectaMongo($bd);
			$collection	= $m->selectCollection($table);
			$cursor		= $collection->find($query);
			$this->fDesconectaMongo();
			return $cursor;
		}

		public function getMongoId($id){
			return new MongoDB\BSON\ObjectID($id);
		}
		/************************************************************************************
		*************************************************************************************
		**************************             END MONGO              ***********************
		*************************************************************************************
		*************************************************************************************/

		/************************************************************************************
		*************************************************************************************
		**************************                 REDIS              ***********************
		*************************************************************************************
		*************************************************************************************/

		/***
			Methods documented on class.redis.invertred.php
		***/

		private function _getRedisHostCredentials($options=null){
			if($options===null){
				$options = array('hostNumber'=>1);
			}
			if(!array_key_exists('db',$options)){
				$options['db'] = 1;
			}
			if(!array_key_exists('hostNumber',$options)){
				$options['hostNumber'] = 1;
			}
			return array(
								'hostNumber'	=> $options['hostNumber'],
								'host'				=> $this->get('_redisHost_'.$options['hostNumber']),
								"pwd"					=> $this->get('_redisPwd_'.$options['hostNumber']),
								"port"				=> $this->get('_redisPort_'.$options['hostNumber']),
								"db"					=> $options['db']
							);

		}

		private function _connectToRedis($options=null){
			if($this->_redis===null){
				$this->_redis = RedisInvertred::singleton($this);
				$this->_redis->setCredentials($this->_getRedisHostCredentials($options));
			}else{
				$this->_redis->setCredentials($this->_getRedisHostCredentials($options));
			}
		}

		//for custom operations
		public function connectToRedis(){
			if($this->_redis===null){
				return $this->_redis->connect();
			}
		}

		public function disconnectFromRedis(){
			return $this->_redis->disconnect();
		}

		public function existsRedisKey($key,$options){
			$this->_connectToRedis($options);
			return	$this->_redis->keyExists($key,$options);
		}

		public function getRedisKey($key,$options){
			$this->_connectToRedis($options);
			return	$this->_redis->getKey($key,$options);
		}

		public function getRedisHashKey($hash,$key,$options){
			$this->_connectToRedis($options);
			return	$this->_redis->getHashKey($hash,$key,$options);
		}

		public function getRedisHash($hash,$options){
			$this->_connectToRedis($options);
			return	$this->_redis->getHash($hash,$options);
		}

		public function getRedisKeys($pattern,$options){
			$this->_connectToRedis($options);
			return $this->_redis->getKeys($pattern,$options);
		}

		public function setRedisKey($key,$value,$options,$ttl=null){
			$this->_connectToRedis($options);
			return $this->_redis->setKey($key,$value,$options,$ttl);
		}

		public function setRedisHashKey($hash,$key,$value,$options){
			$this->_connectToRedis($options);
			return $this->_redis->setHashKey($hash,$key,$value,$options);
		}

		public function setRedisHash($hash,$value,$options){
			$this->_connectToRedis($options);
			return $this->_redis->setHash($hash,$value,$options);
		}

		public function deleteRedisKey($key,$options){
			$this->_connectToRedis($options);
			return $this->_redis->deleteKey($key,$options);
		}

		public function deleteRedisHashKey($hash,$key,$options){
			$this->_connectToRedis($options);
			return $this->_redis->deleteHashKey($hash,$key,$options);
		}

		public function getRedisKeyTTL($key,$options){
			return $this->_redis->getKeyTTL($key,$options);
		}

		public function setRedisKeyTTL($key,$ttl,$options){
			return $this->_redis->setKeyTTL($key,$ttl,$options);
		}

		/************************************************************************************
		*************************************************************************************
		**************************              END REDIS             ***********************
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
