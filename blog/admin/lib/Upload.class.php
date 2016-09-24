<?php
header('Content-Type:text/html;charset=utf-8');
class Upload{
	private $file_mime_list = array(
		'.jpeg' => 'image/jpeg',
		'.jpg' => 'image/jpg',
		'.png' => 'image/png',
		'.gif' => 'image/gif',
		'.html' => 'text/html'
	);
	private $allow_ext_list = array('.jpg','.jpeg','.png','.gif');
	private $allow_max_size = 1048576;
	private $path = './upload/';
	private $prefix = 'ljy_';

	public function setExtlist($allow_ext_list){
		$this->allow_ext_list=$allow_ext_list;
	}

	public function setMaxsize($allow_max_size){
		$this->allow_max_size=$allow_max_size;
	}

	public function setUploadpath($path){
		if(is_dir($path) && is_writeable($path)){
			$this->path=$path;
		}
		
	}

	public function setPrefix($prefix){
		$this->prefix=$prefix;
	}

	
	public function uploadFile($file){
		//1.判断文件是否出错
		if(0 != $file['error']){
			trigger_error('上传文件错误');
			return false;
		}

		//2.判断文件的类型
		//2.1判断文件的后缀
		//2.1.1允许的后缀名列表
		// $allow_ext_list = array('.jpg','.jpeg','.png','.gif');
		//2.1.2获取文件的后缀名
		$ext = strrchr($file['name'],'.');
		//2.1.3判断文件后缀名是否在后缀名列表中
		if(!in_array($ext,$this->allow_ext_list)){
			trigger_error('文件后缀名不符合');
			return false;
		}

		//2.2判断文件的MIME类型，不用$file['type']的原因是这个类型不是固定的，容易被修改，造成影响，所以我们用PHP自带的MIME类型
		// $allow_mime_list = array('image/jpg','image/jpeg','image/png','image/gif');
		$allow_mime_list = $this->getMIME($this->allow_ext_list);
		$finfo = new Finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($file['tmp_name']);

		//2.2.1判断文件类型是否在规定的文件列表里
		if(!in_array($mime,$allow_mime_list)){
			trigger_error('文件类型不符合');
			return false;
		}

		//3.判断文件大小
		// $allow_max_size = 1*1024*1024;
		if($file['size']>$this->allow_max_size){
			trigger_error('文件大小超过允许值');
			return false;
		}

		//4.文件上传路径
		// $path = './upload/';
		//4.1创建子目录
		$subdir = @date('YmdH').'/';
		//4.2判断子目录是否存在
		if(!is_dir($this->path.$subdir)){
			mkdir($this->path.$subdir);
		}
		//4.3获取名字，科学起名
		// $prefix = 'ljy_';
		$basename = uniqid($this->prefix,true).$ext;

		//5.移动文件
		$result_move = move_uploaded_file($file['tmp_name'],$this->path.$subdir.$basename);
		if(!$result_move){
			trigger_error('文件移动失败，联系管理员');
			return false;
		}

		//6.上传成功了，返回文件名
		return $subdir.$basename;
	}

	public function getMIME($ext){
		$mime_list = [];
		foreach($ext as $value){
			$mime_list[] = $this->file_mime_list[$value];
		}

		return $mime_list;
	}

}

?>