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

	//构造方法初始化
	private function __construct(array $option){
		$this->_initConfig($option);
		$this->_initIntance($option);
	}

	//初始化时给属性赋值
	public function _initConfig($option){
		$this->_host = isset($option['host']) ? $option['host'] : '';
		$this->_user = isset($option['user']) ? $option['user'] : '';
		$this->_pwd = isset($option['pwd']) ? $option['pwd'] : '';
		$this->_port = isset($option['port']) ? $option['port'] : '';
		$this->_dbname = isset($option['dbname']) ? $option['dbname'] : '';
		$this->_charset = isset($option['charset']) ? $option['charset'] : 'utf8';
	}

	//实例化一个mysqli类的对象
	public function _initIntance($option){
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
	public static function getSingleten(array $option){
		if(self::$_instance instanceof self){
			self::$_instance=new self($option);
		}
		return self::$_instance=new self($option);
	}

	//禁止克隆
	private function __clone(){
	}
	
	public function query1($sql){
		$res=$this->mysqli->query($sql);
		return $res;
	}

	//执行SQL语句
	public function _query($sql){
		$res=$this->mysqli->query($sql);
		if(!$res){
			die('执行错误'.$this->mysqli->error);
		}
		return $res;
	}

	//执行查询的sql语句，得到一个查询结果集数组
	public function _fetchAll($sql){
		$res=$this->_query($sql);
		$rows = array();
		while($row = $res->fetch_assoc()){
			$rows[] = $row;
		}
		$res->free();
		return $rows;
	}

	//执行查询语句，得到查询结果中的第一行
	public function _fetchRow($sql){
		$res=$this->_query($sql);
		$row = $res->fetch_assoc();
		$res->free();
		return $row;
	}

	//执行查询语句，得到结果集中的一列
	public function _fetchCols($sql){
		$res=$this->_query($sql);
		$row = $res->fetch_assoc();
		$res->free();
		return $row['id'];
	}

	public function _lastInsert(){
		return $this->mysqli->insert_id;
	}

//	public function _page(){
//		$page_size=2;
//		$total_num=
//	}
}
?>