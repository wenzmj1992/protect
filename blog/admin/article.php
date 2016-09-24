<?php
	$action=isset($_GET['a'])?$_GET['a']:'list';
	require './lib/admin_init.php';
	
	if($action=='list'){
		//1.判断是否有page参数传来
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		if($page<=0){
			$page = 1;
		}

		//2.设置偏移量和每页显示数量
		$pagesize = 5;
		$offset = ($page-1)*$pagesize;
		//3.拼凑limit语句
		$limit = "LIMIT $offset,$pagesize";
		//4.执行语句
		$sql="select a.id,a.title,a.status,a.add_time,b.title c from tn_article a left join category b on a.category_id=b.id {$limit}";
		$article_list=$mysqli->_fetchAll($sql);

		
		$sql = "SELECT count(*) as count FROM tn_article";
		//5.总记录数
		$row = $mysqli->_fetchRow($sql);
		//6.得到总数count
		$total = $row['count'];
		//7.得到总页数
		$total_page = ceil($total/$pagesize);

		require ADMIN_TEP_PATH.'article_list.html';
	}else if($action=='add'){
		//跳转到添加页面
		//获取文章分类数据
		$sql="SELECT  id,title FROM `category` ORDER BY order_number";
		$category_list=$mysqli->_fetchAll($sql);
		require ADMIN_TEP_PATH.'article_add.html';
	}else if($action=='insert'){
		$title  = empty($_POST['subject']) ? '' : $_POST['subject'];
		$content = empty($_POST['content']) ? '' : $_POST['content'];
		$summary = empty($_POST['summary']) ? '' : $_POST['summary'];
		//php 中的 time 函数就是获取当前的时间戳
		$add_time = time();
		$user_id = $_SESSION['user']['id'];
		// $cover = empty($_POST['cover']) ? 'mr.jpg':$_POST['cover'];
		$category_id = empty($_POST['category_id']) ? '':$_POST['category_id'];
		$status = empty($_POST['submit']) ? '':$_POST['submit'];
		$is_delete = 0;
		$tags = '';
		//对数据进行简单验证
		//处理封面
		//载入上传工具类
		require ADMIN_LIB_PATH.'Upload.class.php';
		$upload=new Upload();
		$upload->setUploadpath(UPLOAD_PATH.'cover/');
		$upload->setPrefix('cover_');
		$upload_result=	$upload->uploadFile($_FILES['cover']);
		if($upload_result){
			$cover = $upload_result;
			header('Refresh:3;url=article.php?a=list');
			exit;
		}else{		
			echo '上传失败';
			header('Refresh:3;url=article.php?a=add');
			exit;
		}

		if($title == '' || $content == '' || $summary == ''){
			echo '你的输入有误';
			header('Refresh:3;url=article.php?a=add');
			exit;
		}	

		$sql="INSERT INTO tn_article values(null,'$title','$content','$add_time','$user_id','$category_id','$summary','$cover','$status','$is_delete','$tags')";
		
		if($mysqli->_query($sql)){
			header('Location:article.php?a=list');
		}else{
			echo '添加失败';
			header('Location:article.php?a=add');
			exit;
		}



	}else if($action=='delete'){
		$art_id=isset($_GET['art_id']) ? (int)$_GET['art_id'] : null;
		if(!$art_id){
			header('Location:article.php?a=list');
			die;
		}
		$sql="delete from tn_article where id='$art_id'";
		$res=$mysqli->_query($sql);
		if(!$res){
			echo '删除失败';
			header('Location:article.php?a=list');
		}else{
			echo '删除成功';
			header('Location:article.php?a=list');
		}
	}else if($action=='edit'){
		$id=isset($_GET['id']) ? (int)$_GET['id'] : null;
		if(!$id){
			header('Location:article.php?a=list');
			die;
		}
		// $sql="select * from (select a.id a_id,a.title a_tl,a.content content,b.title b_tl,b.id b_id,a.summary summary from tn_article a  left join category b on a.category_id=b.id) as aa where a_id={$art_id}";
		$sql="SELECT * FROM `tn_article` WHERE id=$id";
		$article=$mysqli->_fetchRow($sql);
		$sql="select * from category order by `order_number`";
		$category_list=$mysqli->_fetchAll($sql);
		require ADMIN_TEP_PATH.'article_edit.html';

	}else if($action=='update'){
		$id  = empty($_POST['id']) ? '' : $_POST['id'];
		$title  = empty($_POST['subject']) ? '' : $_POST['subject'];
		$content = empty($_POST['content']) ? '' : $_POST['content'];
		$summary = empty($_POST['summary']) ? '' : $_POST['summary'];
		//php 中的 time 函数就是获取当前的时间戳
		$add_time = time();
		$user_id = $_SESSION['user']['id'];
		//获取当前的id下的封面图片地址
		$sql = "SELECT `cover` FROM tn_article where id=$id";
		//得到目前id下的图片
		$old_cover = $mysqli->_fetchRow($sql);
		require ADMIN_LIB_PATH.'Upload.class.php';
		$upload=new Upload();
		$upload->setUploadpath(UPLOAD_PATH.'cover/');
		$upload->setPrefix('cover_');
		$upload_result=	$upload->uploadFile($_FILES['cover']);
		if($upload_result){
			$cover = $upload_result;
			//判断old_cover旧的图片是否存在
			if($old_cover){
				//旧的图片存在的话删除旧的图片
				unlink(UPLOAD_PATH.'cover/'.$old_cover);
			}
		}else{		
			//上传失败，此时考虑是否要删除旧的图片
			if(isset($_POST['delete'])){
				if($old_cover){
					//旧的图片存在的话删除旧的图片
					unlink(UPLOAD_PATH.'cover/'.$old_cover);
				}
			}
		}

		$category_id = empty($_POST['category_id']) ? '':$_POST['category_id'];
		$status = empty($_POST['submit']) ? '':$_POST['submit'];
		$is_delete = 0;
		$tags = '';
		//对数据进行简单验证
		if($title == '' || $content == '' || $summary == ''){
			echo '你的输入有误';
			header('Refresh:3;url=article.php?a=add');
			exit;
		}	
		

		$sql="update tn_article set title='$title',content='$content',add_time='$add_time',category_id='$category_id',summary='$summary',cover='$cover' where id='$id'";

		if($mysqli->_query($sql)){
			header('Location:article.php?a=list');
			exit;
		}else{
			echo '添加失败';
			header('Location:article.php?a=add');
			exit;
		}

	}






?>