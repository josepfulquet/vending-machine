<?php
namespace jfc\utils;
require 'includes/vendor/autoload.php';
class Mongojfc{

	private $_servidor;
	private $_database;
	private $_logger;

	private static $instancia = null;
	private $db;
	private $m;
	private $_conectado;
	function __construct(){
		$system = System::singleton();

	}
	public function setDataBD($server,$database,$user,$pwd){
		$this->_servidor	= $server;
		$this->_database	= $database;
		$this->_user			= $user;
		$this->_password	= $pwd;
	}

	public function fDameId($col){

		$mongo				= $this->fConecta();

		$collection=$mongo->counter;
		$collection->update(
			array('col'=>$col),
			array('$inc'=>array('value'=>1)),
			array('upsert'=>true)
		);

		$cursor = $collection->find(array('col'=>$col));
		$resultados=array();
		if($cursor->count()>0){
			foreach ($cursor as $obj) {
				$id	= $obj['value'];

			}
		}

		$this->fDesconectaMongo();

		return $id;
	}

	public function fConecta(){
		if($this->_user!=""){
			$con="mongodb://".$this->_user.":".$this->_password."@".$this->_servidor."/".$this->_database;
		}else{
			$con=$this->_servidor;
		}

		$this->m = new MongoDB\Client($con);
		$db = $this->m->selectDatabase($this->_database);
		return $db;
	}
	public function fDesconectaMongo(){
		//$this->m->close();
		$this->m = null;
	}

	//Con set vamos guardando nuestras variables.
  public function set($name, $value){
  	//echo $name.$value;
  	$this->$name = $value;

  }

  //Con get('nombre_de_la_variable') recuperamos un valor.
  public function get($name)
  {
    if(isset($this->vars[$name])){
      return $this->$name;
    }
  }

	public static function singleton()	{
		if( self::$instancia == null )
		{
			self::$instancia = new self();
		}
		return self::$instancia;
	}

}
?>
