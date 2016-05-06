<?php
/**
 * 退出登录
 */
class logoutAction extends homeBaseAction{

	
    public function __init(){
		$this->debug=false;
    }
	
	/**
	 * 退出登录
	 * @access public 
	 * @return html
	 */
	public function init(){
		if($this->user_id>0){
			M('user:passport')->setSession();
		}
		$this->forward('/');exit;
	}
}