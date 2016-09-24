<?php

class SessionMySQL {
	private $mysqli;

	public function __construct(){
		ini_set('session.save_handler','user');
		ini_set('session.gc_probability', '1');
		// 除数
		ini_set('session.gc_divisor', '20');
		// // 有效期
		ini_set('session.gc_maxlifetime', 1440);
		session_set_save_handler(
            array($this, "sessionBegin"),
            array($this, "sessionEnd"),
            array($this, "sessionRead"),
            array($this, "sessionWrite"),
            array($this, "sessionDelete"),
            array($this, "sessionGc")
        );
        session_start();

	}
	public function sessionRead($session_id){
		$sql="SELECT session_data FROM `session` WHERE  session_id='$session_id'";
		$row = $this->mysqli->_fetchRow($sql);
		return $row ? $row['session_data'] : '';
	}

	public function sessionWrite($session_id,$session_data){
		$sql="REPLACE INTO session values('$session_id','$session_data',unix_timestamp())";

		if(isset($this->mysqli)){
			return $this->mysqli->_query($sql);
		}else{
			$mysqli=new MySQLi('localhost','root','123456','myblog','3306');
			$mysqli->set_charset('utf8');
			return $mysqli->_query($sql);
		}
	}

	public function sessionDelete($session_id){
		$sql="DELETE FROM `session` WHERE `session_id`='$session_id'";
		return $this->mysqli->_query($sql);
	}

	public function sessionGc($maxlifetime){
		$sql="DELETE FROM session WHERE (unix_timestamp() - `last_time`)>'$maxlifetime'";
		$res=$this->mysqli->_query($sql);
		return $this->mysqli->_query($sql);
	}

	public function sessionBegin(){
		 require_once LIB_PATH.'DAOMySQLi.class.php';
		 $config=array(
			'host' => 'localhost',
			'user' => 'root',
			'pwd' => '123456',
			'port' => '3306',
			'dbname' => 'myblog',
			'charset' => 'utf8'
		);
		$this->mysqli = DAOMySQLi::getSingleten($config);

	}

	public function sessionEnd(){
		return true;
	}
}






?>