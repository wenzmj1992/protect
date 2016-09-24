<?php
$category_action = isset($_GET['a']) ? $_GET['a'] : 'list';
require './lib/admin_init.php';

if($category_action=='list'){
	$sql="select * from category";
	$category_list=$mysqli->_fetchAll($sql);
	require ADMIN_TEP_PATH.'category_list.html';
}else if($category_action=='add'){
	require ADMIN_TEP_PATH.'category_add.html';
}else if($category_action=='insert'){
	$title=empty($_POST['title']) ? '' : $_POST['title'];
	$order_number=isset($_POST['order_number'])&&is_numeric($_POST['order_number']) ? $_POST['order_number'] : '';
	if($title=='' || $order_number==''){
		echo '输入的数据有问题';
		header('Refresh:3;url=category.php?a=add');
		exit;
	}
	$sql="insert into category values(null,'$title','$order_number')";
	if($mysqli->_query($sql)){
		header('Location:category.php?a=list');
		exit;
	}else{
		echo '输入的数据有问题';
		header('Refresh:3;url=category.php?a=add');
		exit;
	}
	
}else if($category_action=='delete'){
	$id=$_GET['id'];
	$sql="delete from category where id='$id'";
	$res=$mysqli->_query($sql);
	if(!res){
		echo '删除失败';
		header('Location:category.php?a=list');
		exit;
	}else{
		echo '删除成功';
		header('Location:category.php?a=list');
		exit;
	}
}else if ($category_action=='edit'){
	$id=$_GET['id'];
	$sql="select * from category where id='$id'";
	$category=$mysqli->_fetchRow($sql);
	require ADMIN_TEP_PATH.'category_edit.html';
}else if($category_action=='update'){
	$id=$_GET['id'];
	$title=$_POST['title'];
	$order_number=$_POST['order_number'];
	$sql="update category set title='$title',order_number='$order_number' where id='$id'";
	$res=$mysqli->_query($sql);
	if(!$res){
		echo '更新失败';
		header('Location:category.php?a=list');
	}else{
		echo '更新成功';
		header('Location:category.php?a=list');
	}
}




?>