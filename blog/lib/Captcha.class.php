<?php
class Captcha{
	//需要的验证码值得数量
	private $code_length=4;
	//画布图片的宽度
	private $width=350;
	//画布图片的高度
	private $height=30;
	//字体的大小
	private $font=5;
	//干扰元素的数量
	private $pixel_num=300;

	public function setCodelength($code_length){
		$this->code_length=$code_length;
	}

	public function setwidth($width){
		$this->width=$width;
	}

	public function setheight($height){
		$this->height=$height;
	}

	public function setfont($font){
		$this->font=$font;
	}

	public function setpixel_num($pixel_num){
		$this->pixel_num=$pixel_num;
	}
	
	public function mkImage(){
		//1.可能的码值
		$char_list = 'ABCDEFGHIJKLMNPQRSTUVWSYZ123456789';
		$char_length = $this->code_length;
		$code = '';
			//1.1得到四个随机的码值
		for($i=0;$i<$char_length;++$i){
			$index = mt_rand(0,strlen($char_list)-1);
			$code .= $char_list[$index];
		}

		//2.存储于session中
		@session_start();
		$_SESSION['captcha_code'] = $code;

		//3.制作验证码图像
			//3.1生成画布
		$image = imagecreatetruecolor($this->width,$this->height);
			//3.2选择颜色
		$imagecolor = imagecolorallocate($image,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			//3.3开始填充
		$imagefill = imagefill($image,0,0,$imagecolor);
			//3.4设置字符码值
			//3.4.1设置字符颜色
		$code_color = imagecolorallocate($image,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
			//3.4.2取得字体的宽度和高度
		$fontwidth = imagefontwidth($this->font);
		$fontheight = imagefontheight($this->font);
			//3.4.3取得字体的位置坐标x和y
		$code_x = ($this->width-$fontwidth*4)/2;
		$code_y = ($this->height-$fontheight)/2;
			//3.4.4水平画一行字符
		$imagestring = imagestring($image,$this->font,$code_x,$code_y,$code,$code_color);
			//3.5生成干扰元素点
				//设置干扰元素属性4个
				//循环生成多个干扰元素
		for($i=0;$i<$this->pixel_num;++$i){
			$pixel_color = imagecolorallocate($image,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
			$imagesetpixel = imagesetpixel($image,mt_rand(0,$this->width-1),mt_rand(0,$this->height-1),$pixel_color);
		}

		//4.输出验证码图片
		
		header('Content-Type:image/png');
			//控制浏览器不要缓存
		header('Cache-Control: no-cache no-store must-revalidate');
		header('Exipres: ' .gmdate('D,Y-m-d H:i:s' , time()-1 ). 'GMT');
		imagepng($image);

		//5.销毁验证码图像
		imagedestroy($image);
	}
	

}






?>