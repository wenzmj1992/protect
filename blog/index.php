<?php
require './lib/init.php';
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
$where = "status='publish'";
if(!$cat_id == ''){
	$where .= "AND category_id=$cat_id";
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//检查其合理性，只需要正整数
if($page<=0){
	//不是合理的页码数，默认是1
	$page = 1;
}
//预设每页的记录数
$pagesize = 2;
//计算offset
$offset = ($page - 1) * $pagesize;
//拼凑limit
$limit_str = "LIMIT $offset,$pagesize";
//添加到SQL，执行
$sql="SELECT * FROM tn_article WHERE $where $limit_str";
$article_list = $mysqli->_query($sql);
	// 处理与翻页连接相关数据
	// 总记录数
$sql = "SELECT count(*) AS article_count FROM tn_article where $where";
$row = $mysqli->_fetchRow($sql);
$total = $row['article_count'];
//总页码数
// $total_page = ceil($total/$pagesize);
require LIB_PATH.'Page.class.php';
$tool_page = new Page;
$url_param = [];
if($cat_id !== ''){
	$url_param['cat_id'] = $cat_id;
}

$page_html = $tool_page->show($page,$pagesize,$total,'index.php',$url_param);

//取出分类信息

$sql="SELECT id,title FROM `category` ORDER BY order_number";
$category_list = $mysqli->_fetchAll($sql);
require TEP_PATH.'index.html';
?>