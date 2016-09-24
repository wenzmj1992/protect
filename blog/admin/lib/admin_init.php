<?php
header("content-type:text/html;charset:utf-8");
//定义目录路径常量
define('ROOT_PATH',dirname(dirname(__DIR__)).'/');
define('LIB_PATH',ROOT_PATH.'lib/');
define('ADMIN_PATH',ROOT_PATH.'admin/');
define('ADMIN_TEP_PATH',ADMIN_PATH.'templete/');
define('ADMIN_LIB_PATH',ADMIN_PATH.'lib/');
define('UPLOAD_PATH',ROOT_PATH.'upload/');
//定义后台路径常量

//设置时区
date_default_timezone_set ( 'PRC' );
//require LIB_PATH.'DAOMySQLi.class.php';
require_once LIB_PATH.'DAOMySQLi.class.php';
$config=array(
	'host' => 'localhost',
	'user' => 'root',
	'pwd' => '123456',
	'port' => '3306',
	'dbname' => 'myblog',
	'charset' => 'utf8'
);
$mysqli = DAOMySQLi::getSingleten($config);
require_once LIB_PATH.'SessionMySQL.class.php';
new SessionMySQL;

// session_start();
/*
	这里我们去读取session文件，
	1.如果有，则说明该用户已经成功登录，
	2.否则，说明该用户没有登录就想进入后台对应第二种，将其返回登录页面.
	*/

	//对于正常的登录请求，需要放行
	//我们需要考虑，什么样的情况下，不要去验证是否登录
	// 1. 请求的是 user.php 文件，并且 action=login 或者 action=check
	//echo '<pre>';
	//var_dump(basename($_SERVER['SCRIPT_NAME']));
$request_filename = basename($_SERVER['SCRIPT_NAME']);

if(!($request_filename == 'user.php' && ($action == 'login' || $action == 'check' ))){
		// var_dump($_SESSION['user']);//|| $action=='captcha'
		// die;
	if(!isset($_SESSION['user'])){

		if(isset($_COOKIE['id']) && isset($_COOKIE['password'])){

			$id=$_COOKIE['id'];
			$password=$_COOKIE['password'];
			$sql="select * from tn_user where md5(id)='$id' and md5(password)='$password'";

			$user=$mysqli->_fetchRow($sql);

			if($user){

				$_SESSION['user']=$user;
				// var_dump($_SESSION['user']);
				// die;
			}else{
				echo '登录失败';


				header('Location:user.php?a=login');
				exit;
			}

		}else{
			header('Location:user.php?a=login');
			exit;
		}

		
	}
}

require ADMIN_LIB_PATH . 'function.php';
$_POST = deepEscape($_POST);
$_GET = deepEscape($_GET);
?>