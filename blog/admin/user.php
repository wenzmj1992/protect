<?php 
$action = isset($_GET['a']) ? $_GET['a'] : 'login';
require './lib/admin_init.php';
// require ADMIN_TEP_PATH.'login.html';



if($action=='login'){
	require ADMIN_TEP_PATH.'login.html';
}else if($action=='check'){
	require LIB_PATH.'Captcha.class.php';
	// $captcha = new Captcha();
	// $captcha_code = empty($_POST['captcha']) ? '' : $_POST['captcha'];
	// if(!$captcha_code){
	// 	echo "<script>alert('请输入验证码');
	// 	location.href='user.php?a=login';
	// 	</script>";
	// 	die;
	// }else if(!$captcha->checkCode($captcha_code)){
	// 	echo "<script>alert('验证码输入有误');
	// 	location.href='user.php?a=login';
	// 	</script>";
	// 	die;
	// }

	//1.接受数据
	$username=empty($_POST['username']) ? '' : $_POST['username'];
	$password=empty($_POST['password']) ? '' : $_POST['password'];
	if($username=='' || $password==''){
		echo "<script>alert('你的用户名或者密码输入有误');
		location.href='user.php?a=login';
		</script>";
		// echo '你的用户名或者密码输入有误';
		// header('Refresh:2;url=user.php?a=login');
		exit;
		//header('Location:templete/error.html');
	}
	$sql="select * from tn_user where username='$username'";
	$res=$mysqli->_fetchRow($sql);
	if($username){
		if($res['password']==$password){	
			
			$_SESSION['user']=$res;

			if(isset($_POST['remember_me'])){
				setcookie('id',md5($res['id']+'salt'),time()+7*2*24*3600);
				setcookie('password',md5($res['password']+'salt'),time()+7*2*24*3600);
			}

			header('Location:index.php');
			exit;
		}else{
			//用户存在，密码有误
			echo "<script>alert('你的密码有误');
			location.href='user.php?a=login';
			</script>";
			// echo '你的密码有误';
			// header('Refresh:2;url=user.php?a=login');
			exit;
		}
	}else{
		echo "<script>alert('你的用户名不存在');
			location.href='user.php?a=login';
			</script>";
		// echo '你的用户名不存在';
		// header('Refresh:2;url=user.php?a=login');
		exit;
	}
	
}else if($action=='logout'){
	if($_COOKIE){
			setcookie('id', md5($user['id'].'salt'), time()-1);
			setcookie('password', md5($user['password'].'salt'), time()-1);
			
	}
	session_destroy();
	setcookie('PHPSESSID', '', time()-1,'/');
	unset($_SESSION['user']);
	header('Location:user.php?a=login');
}else if($action=='captcha'){
	//使用验证码类
	require LIB_PATH.'Captcha.class.php';
	//设置验证码属性
	$captcha = new Captcha();
	// $captcha->setCodelength();
	// $captcha->setWidth();
	// $captcha->setHeight();
	// $captcha->setFont();
	// $captcha->setPixel_number();
	// $captcha->mkImage();
	//生成验证码图像
	$captcha->mkImage();

}


?>