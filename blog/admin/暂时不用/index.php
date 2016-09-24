<?php
require '../lib/admin_init.php';
$username=isset($_POST['username']) ? $_POST['username'] : '';
$password=isset($_POST['password']) ? $_POST['password'] : '';
$sql="select * from user where username='{$username}' and password='{$password}'";
$res=$mysqli->query1($sql);
$row_num=$res->num_rows;
if($username=='' || $password==''){
	echo "<script>alert('用户名或者密码不能为空');history.back()</script>";
	exit;
}else if($row_num==0){
	echo "<script>alert('用户名或者密码不正确');history.back();</script>";
	exit;
}

$sql="select * from user where username='$username'";
$info=$mysqli->_fetchRow($sql);
require ADMIN_TEP_PATH.'index.html';

?>