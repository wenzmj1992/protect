<?php
//初始化字符编码
header("content-type:text/html;charset:utf-8");
//定义目录路径常量
define('ROOT_PATH',dirname(__DIR__).'/');
define('LIB_PATH',ROOT_PATH.'lib/');
define('TEP_PATH',ROOT_PATH.'templete/');
//定义后台路径常量

//设置时区
date_default_timezone_set ( 'PRC' );
require LIB_PATH.'DAOMySQLi.class.php';
$config=array(
	'host' => 'localhost',
	'user' => 'root',
	'pwd' => '123456',
	'port' => '3306',
	'dbname' => 'myblog',
	'charset' => 'utf8'
);
$mysqli = DAOMySQLi::getSingleten($config);
?>