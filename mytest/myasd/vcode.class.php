<?php
/**
 * 生成验证码
 * 类用法
 * $vcode = new vcode();
 * $vcode->doimage();
 * $_SESSION['code']=$checkcode->get_code();
 */
class vcode{
	
	//验证码的宽度
	public $width=100;
	
	//验证码的高
	public $height=30;
	
	//设置字体的地址
	private $font;
	
	//设置字体色
	public $font_color;
	
	//设置随机生成因子
	public $charset = 'abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ123456789';

	public $charset2 = '1234567890';
	
	public $seedtype = 1;

	//设置背景色
	public $background = '#EDF7FF';
	
	//生成验证码字符数
	public $code_len = 4;
	
	//字体大小
	public $font_size = 13;
	
	//验证码
	private $code;
	
	//图片内存
	private $img;
	
	//文字X轴开始的地方
	private $x_start;
		
	function __construct() {
		$this->font = CORE_PATH.'data/font/elephant.ttf';
	}
	
	/**
	 * 生成随机验证码
	 */
	protected function creat_code() {
		$this->charset = $this->seedtype == 1 ? $this->charset :  $this->charset2;
		$code = '';
		$charset_len = strlen($this->charset)-1;
		for ($i=0; $i<$this->code_len; $i++) {
			$code .= $this->charset[rand(1, $charset_len)];
		}
		$this->code = $code;
	}
	
	/**
	 * 获取验证码
	 */
	public function get_code() {
		return strtolower($this->code);
	}
	
	/**
	 * 生成图片
	 */
	public function doimage() {
		$code = $this->creat_code();
		//建立一幅w*h的图像
		$this->img = imagecreatetruecolor($this->width, $this->height);
		//设定文字颜色
		if (!$this->color) {
			$this->color = imagecolorallocate($this->img, rand(0,156), rand(0,156), rand(0,156));
		} else {
			$this->color = imagecolorallocate($this->img, hexdec(substr($this->color, 1,2)), hexdec(substr($this->color, 3,2)), hexdec(substr($this->color, 5,2)));
		}
		//设置背景色
		$background = imagecolorallocate($this->img,hexdec(substr($this->background, 1,2)),hexdec(substr($this->background, 3,2)),hexdec(substr($this->background, 5,2)));
		//画一个柜形，设置背景颜色。
		imagefilledrectangle($this->img,0, $this->height, $this->width, 0, $background);
		$this->creat_font();
		// $this->creat_line();
		$this->output();
	}
	
	/**
	 * 生成文字
	 */
	private function creat_font() {
		$x = $this->width/$this->code_len;
		for ($i=0; $i<$this->code_len; $i++) {
			//将字符写入图片：文字大写/字型的角度（0水平）/文字坐标x/坐标y/文字颜色/字体/文字内容
			#ImageTTFText(int im, int size, int angle, int x, int y, int col, string fontfile, string text)
			imagettftext($this->img, $this->font_size, rand(-30,30), $x*$i+rand(0,5), $this->height/1.4, $this->color, $this->font, $this->code[$i]);
			if($i==0)$this->x_start=$x*$i+5;
		}
	}
	
	/**
	 * 画随机干扰线
	 */
	private function creat_line() {
		//干扰点
        for($i=0;$i<10;$i++){
            imagesetpixel($this->img,rand(0,$this->width),rand(0,$this->height),$this->color);
        }		
		
		//把画矩形/多边形/椭圆等等时所用的线宽x像素
		imagesetthickness($this->img, rand(1,3));
	    $xpos   = ($this->font_size * 2) + rand(-5, 5);
	    $width  = $this->width /2 + rand($this->width/5, $this->width/2);
	    $height = $this->font_size * 2.14;
	
	    if ( rand(0,100) % 2 == 0 ) {
	      $start = rand(0,66);
	      $ypos  = $this->height / 2 - rand(10, 30);
	      $xpos += rand(5, 15);
	    } else {
	      $start = rand(180, 246);
	      $ypos  = $this->height / 2 + rand(10, 30);
	    }
	    $end = $start + rand(50, 60);
		
		#画椭圆弧:以cx，cy（图像左上角0,0）为中心在 image 所代表的图像中画一个椭圆弧。w 和 h 分别指定了椭圆的宽度和高度，起始和结束点以 s 和 e 参数以角度指定
		#imagearc ( resource $image , int $cx , int $cy , int $w , int $h , int $s , int $e , int $color )
		imagearc($this->img, $xpos, $ypos, $width, $height, $start, $end, $this->color);
		
		imagesetthickness($this->img, rand(1,3));
	    if ( rand(1,75) % 2 == 0 ) {
	      $start = rand(45, 111);
	      $ypos  = $this->height / 2 - rand(10, 30);
	      $xpos += rand(5, 15);
	    } else {
	      $start = rand(200, 250);
	      $ypos  = $this->height / 2 + rand(10, 30);
	    }
	
	    $end = $start + rand(50, 60);
	
	    imagearc($this->img, $this->width * .85, $ypos, $width, $height, $start, $end, $this->color);
	}
	
	/**
	 * 输出图片
	 */
	private function output() {
		header("content-type:image/png\r\n");
		imagepng($this->img);
		imagedestroy($this->img);
	}

	/**
	 * 生成图片
	 */
	public function do_file_image($path) {
		$code = $this->creat_code();
		//建立一幅w*h的图像
		$this->img = imagecreatetruecolor($this->width, $this->height);
		//设定文字颜色
		if (!$this->color) {
			$this->color = imagecolorallocate($this->img, rand(0,156), rand(0,156), rand(0,156));
		} else {
			$this->color = imagecolorallocate($this->img, hexdec(substr($this->color, 1,2)), hexdec(substr($this->color, 3,2)), hexdec(substr($this->color, 5,2)));
		}
		//设置背景色
		$background = imagecolorallocate($this->img,hexdec(substr($this->background, 1,2)),hexdec(substr($this->background, 3,2)),hexdec(substr($this->background, 5,2)));
		//画一个柜形，设置背景颜色。
		imagefilledrectangle($this->img,0, $this->height, $this->width, 0, $background);
		$this->creat_font();
		$this->creat_line();
		$this->file_output($path);
	}

	/**
	 * 输出图片
	 */
	private function file_output($path) {
		header("content-type:image/png\r\n");
		imagepng($this->img,$path);
		imagedestroy($this->img);
	}
}
?>