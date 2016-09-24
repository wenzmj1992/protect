<?php
header("content-type:text/html;charset:utf-8");
/**
*基于MySQLi的数据库抽象层对象(DAO)
*/
class DAOMySQLi {
	private $_host;
	private $_user;
	private $_pwd;
	private $_port;
	private $_dbname;
	private $_charset;
	//存储当前的类的唯一实例
	private static $_instance;
	//存储mysqli类对象
	private $mysqli;

	//增删改返回的被影响的行数
	private $affected_rows;
	//获得查询后的mysqli_result类对象
	private $result;
	//构造方法初始化
	private function __construct(array $option){
		$this->_initConfig($option);
		$this->_initIntance($option);
	}

	//初始化时给属性赋值
	private function _initConfig($option){
		$this->_host = isset($option['host']) ? $option['host'] : '';
		$this->_user = isset($option['user']) ? $option['user'] : '';
		$this->_pwd = isset($option['pwd']) ? $option['pwd'] : '';
		$this->_port = isset($option['port']) ? $option['port'] : '';
		$this->_dbname = isset($option['dbname']) ? $option['dbname'] : '';
		$this->_charset = isset($option['charset']) ? $option['charset'] : 'utf8';
	}

	//实例化一个mysqli类的对象
	private function _initIntance($option){
		//实例化mysqli对象
		$this->mysqli=new MySQLi($this->_host,$this->_user,$this->_pwd,$this->_dbname,$this->_port,$this->_charset);
		//是否连接失败
		if($this->mysqli->error){
			die('mysql服务器连接失败'.$this->mysqli->error);

		}
	}

	public function _initCharset(){
		if(!$this->mysqli->set_charset($this->charset)){
			die('数据库选择字符集失败'.$this->mysqli->error);
		}

	}

	/**
	*获得单例对象的方法
	*@parse array $option ['传入一个配置参数数组']
	*@parse return [object] [返回唯一对象]
	*
	*/
	public static function getSingleton(array $option){
		if(self::$_instance instanceof self){
			self::$_instance=new self($option);
		}
		return self::$_instance=new self($option);
	}

	//禁止克隆
	private function __clone(){
	}

	//执行查询类SQL语句
	public function query($sql=''){
		$res=$this->mysqli->query($sql);
		if(!$res){
			die('执行错误'.$this->mysqli->error);
		}
		$this->result = $res;
		
		return $res;
	}

	// 执行SQL，通常用于执行非查询类
	public function exec($sql=''){
		$res=$this->mysqli->query($sql);
		if(!$res){
			die('执行错误'.$this->mysqli->error);
		}
		$this->affected_rows = $this->mysqli->affected_rows;
		return $res;
	}

	//执行查询的sql语句，得到一个查询结果集数组
	public function fetchAll($sql=''){
		$res=$this->query($sql);
		$rows = array();
		while($row = $res->fetch_assoc()){
			$rows[] = $row;
		}

		// $res->free();
		
		return $rows;
	}

	//执行查询语句，得到查询结果中的第一行
	public function fetchRow($sql=''){
		$res=$this->query($sql);
		$row = $res->fetch_assoc();
		//$res->free();
		return $row;
	}

	//执行查询语句，得到结果集中的一列
	public function fetchOne($sql='',$column='id'){
		$res=$this->query($sql);
		$row = $res->fetch_assoc();
		//$res->free();
		return $row[$column];
	}

	// 提供用于转义 和 引号包裹的方法
	public function escapeData($data=''){
		return $this->mysqli->real_escape_string($data);
	}

	// 获得受影响的记录数，增删改
	public function affectedRows(){
		$affected_rows = $this->affected_rows;
		$this->affected_rows = null;
		return $affected_rows;
	}

	// 结果集中的记录数数，查
	public function resultRows(){
		//返回当前结果集对象的总行数
		// var_dump($this->result);
		// die;
		return $this->result->num_rows;
	}

	public function lastInsertID(){
		return $this->mysqli->insert_id;
	}

}
?>