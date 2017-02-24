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
			unset($_SESSION);
			unset($_COOKIE);
			session_destroy();
			$cache=cache::startRedis();
			$cache->set('userid_'.SESS_ID,0);
			$cache->set('uinfo_'.SESS_ID,null);
			M('user:passport')->setSession();


		}
		$this->forward('/');exit;
	}
}