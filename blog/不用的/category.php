<?php
require './lib/admin_init.php';
$id=isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : '';
if(!$id){
	$info = <<<INFO
	<script>alert('选择分类出错');history.back();</script>
INFO;
	echo $info;
}
$sql="select * from tn_article where category_id=$id";
$category_list = $mysqli->_fetchAll($sql);
require TEP_PATH.'category.html';




?>