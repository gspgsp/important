<?php
/*
 * 前台验证码
*/

class vcodeAction extends homeBaseAction {
    public function __init(){
    }

	/**
	 * 获取验证
	 * @access public 
	 * @return html
	 */
	public function init(){
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

    /**
     * 检查验证码
     * @access public
     * @return html
     */
	public function chkVcode(){
		$name=sget('name','s');
		$value=sget('value','s');
		$value=strtolower($value);
		
		if(!chkVcode($name,$value)){
			$this->error('验证码输入不正确');	
		}else{
			$this->success('验证成功');
		}
	}
}

?>