<?php
/**
 * 前端控制器
 */
class null2Action extends action {
	protected $ssid=NULL;
	protected $user_id=0;
	protected $token='';
	protected $debug = false;
	public function __construct() {
		parent::__construct();
// 		$this->sys = M('system:setting')->getSetting();
		//网站主题
		$theme_path = ($this->sys['theme'] ?: 'default') . '/';
		$this->view->template_dir .= $theme_path;
		$this->view->compile_dir .= $theme_path;
		$this->view->cache_dir .= $theme_path;
		if(!is_robot()){ //非机器人访问
		    startHomeSession();
		    $cache=cache::startRedis();
		    $_SESSION['userid']=($cache->get('userid_'.SESS_ID)==false?0:$cache->get('userid_'.SESS_ID));
		    $_SESSION['uinfo']=($cache->get('uinfo_'.SESS_ID)==false?null:json_decode($cache->get('uinfo_'.SESS_ID)));
		    $this->user_id=$_SESSION['userid'];
		    //$this->dataToken=$_SESSION['token'];
		    setReferer($this->user_id);
		}
	}



	/*
	 * 访问不存在的方法
	 * @access protected
	 * @redirect
	*/
	public function _null(){
// 		header("HTTP/1.0 404 Not Found");
// 		$this->error('您访问的页面不存在','/');
// 		exit;
	}

// 	//获取机器UV
// 	protected function _uv(){
// 		$_from=sget('from','s');
// 		$_cfrom=cookie::get('_from');
// 		if(!empty($_from) && empty($_cfrom)){
// 			cookie::set('_from',$_from);
// 			$_cfrom=$_from;
// 		}
// 		if($_cfrom=='andriod'){
// 			$this->assign('andriod','y');
// 		}

// 		$uv=getUV();
// 		if(empty($uv)){
// 			cookie::set('_uv',genUV(),180*86400);
// 		}
// 		return $uv;
// 	}

}
?>
