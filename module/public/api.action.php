<?php
/**
 * api接口类 
 */
class apiAction extends action{
    /**
     * 初始化控制器
     * @access public
    */
	public function __construct(){
		parent::__construct();
	}
	
    /**
     * 初始化控制器
     * @access public
    */
	public function region(){
		$pid=sget('pid','i');
		$region=M('system:region')->get_regions($pid);
		$result=array('err'=>0,'regions'=>$region);
		$this->json_output($result);
	}
	
    /**
     * 后台验证码
     * @access public
    */
	public function vcode(){
		startAdminSession();		

		$vcode=new vcode();
		$vcode->code_len = 4;
		$vcode->font_size = 14;
		$vcode->width = 80;
		$vcode->height = 36;
		#$vcode->background = "#cccccc";
		$vcode->doimage();
		$name='vc_'.sget('name','s','vcode');
		$_SESSION[$name]=$vcode->get_code();
	}
}

?>