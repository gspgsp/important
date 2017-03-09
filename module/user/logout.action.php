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
			$cache= new RedisCluster();
			$cache->delete('userid_'.SESS_ID);
			$cache->delete('uinfo_'.SESS_ID);
			M('user:passport')->setSession();


		}
		$this->forward('/');exit;
	}
}