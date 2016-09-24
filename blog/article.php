<?php
require './lib/init.php';
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : '';
if(!$id){
	$info=  <<<INFO
	<script>alert('没有记录');
	history.back()</script>
INFO;
	echo $info;
}
$sql="SELECT * FROM tn_article WHERE id={$id}";
$row = $mysqli->_fetchRow($sql);
$sql="SELECT id,title FROM `category` ORDER BY order_number";
$category_list = $mysqli->_fetchAll($sql);
require TEP_PATH.'article.html';


?>