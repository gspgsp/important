<?php
/**
 * 前端控制器
 */
class homeBaseAction extends action {
	protected $plastic_points = NULL;
	protected $ssid=NULL;
	protected $user_id=0;
	protected $token='';
	protected $debug = false;
	const    POSITION= 2;    //合作伙伴
	const    NUMBER  = 4;    //数量
	const    POSITIONS= 3;    //友情链接
	public function __construct() {
		parent::__construct();
		//调用配置项
		$this->plastic_points = M('system:setting')->get('points')['points'];
		if(!is_robot()){ //非机器人访问
			startHomeSession();
			$cache=cache::startRedis();
			$_SESSION['userid']=($cache->get('userid_'.SESS_ID)==false?0:$cache->get('userid_'.SESS_ID));
			$_SESSION['uinfo']=($cache->get('uinfo_'.SESS_ID)==false?null:json_decode($cache->get('uinfo_'.SESS_ID)));
			$this->user_id=$_SESSION['userid'];
			//$this->dataToken=$_SESSION['token'];
			setReferer($this->user_id);
			if(empty($this->user_id)){ //检查令牌
				$token=cookie::get(C('SESSION_TOKEN'));
				if(strlen($token)>30){
					$this->user_id=M('user:passport')->chkToken($token);
					if($this->user_id){
						M('user:passport')->setSession($this->user_id);
					}
				}
			}

			if($this->user_id){
				// 用户拓展信息
				$uinfo = M('user:customerContact')->getInfoById($this->user_id);
				//用户信息
				$this->uinfo = $_SESSION['uinfo'] = array_merge((array)$_SESSION['uinfo'],$uinfo);

				$this->user_name = empty($_SESSION['uinfo']['real_name']) ? substr_replace($_SESSION['uinfo']['mobile'],'****',3,4) : $_SESSION['uinfo']['real_name'];

				$this->unread_msgs_count = M('system:sysMsg')->countUnread($this->user_id);
			}
			//是否需要HTTPs跳转
			if(C('HTTPS_ON')){
				if(!isset($_SERVER['HTTPS'])){ //所有都不必须要跳、或指定模块跳转
					$modules=explode(',',C('HTTPS_MODULE'));
					if(empty($modules[0]) || in_array(ROUTE_M,$modules) || __A__=='/home/index/init'){
						$this->forward(str_replace('http:','https:',APP_URL).__SELF__);
					}
				}else{ //替换静态文件https
					C('TEMP_REPLACE.__JS__',str_replace('http:','https:',C('TEMP_REPLACE.__JS__')));
					C('TEMP_REPLACE.__IMG__',str_replace('http:','https:',C('TEMP_REPLACE.__IMG__')));
					C('TEMP_REPLACE.__CSS__',str_replace('http:','https:',C('TEMP_REPLACE.__CSS__')));
					C('TEMP_REPLACE.__UPLOAD__',str_replace('http:','https:',C('TEMP_REPLACE.__UPLOAD__')));
				}
			}
			$this->_uv();
		}
		//系统信息:赋值模板
		$this->assign('user_id', $this->user_id);
		$sys = M('system:setting')->getSetting();
		// 网站服务人数
		$service = explode('|', $sys['service']);
		$this->assign('service',$service);
		$this->assign('sys',$sys);
		//网站主题
		$theme_path = ($this->sys['theme'] ?: 'default') . '/';
		$this->view->template_dir .= $theme_path;
		$this->view->compile_dir .= $theme_path;
		$this->view->cache_dir .= $theme_path;

		if(strstr($_GET['a'],'&gclid')){ //google推广链接的问题
			$action=$_GET['a']=substr($_GET['a'],0,strpos($_GET['a'],'&'));
			if(method_exists($this,$action)){
				$this->$action();exit;
			}
		}
        //底部分类信息
        $this->footer=M('system:info')->getFooterCate();
		//底部单独关于我们
		$this->aboutUs=M('system:info')->aboutUs();
		//底部 合作伙伴
		$this->Partner=M('system:block')->getBlock(self::POSITION,self::NUMBER);
		//友情链接
	    $this->friendshipLink=M('system:block')->getFriendshipLink(self::POSITIONS);

		//分配action名用于显示导航条的hover效果
		$this->assign('on',$_GET['m']);
		$this->assign('on_1',$_GET['m']);
		$this->assign('on_2',$_GET['m']);
		$this->assign('on_3',$_GET['m']);
		$this->assign('on_4',$_GET['m']);
		$this->assign('on_6',$_GET['m']);
		$this->assign('on_7',$_GET['m']);


	}

	/*
	 * 检查会员是否登录
	 * @access protected
	 * @redirect
	*/
	protected function chkLogin(){
		if($this->user_id<1){
			$_SESSION['gurl']=__SELF__;
			$this->forward('/user/login');
		}
		return true;
	}

	/*
	 * 访问不存在的方法
	 * @access protected
	 * @redirect
	*/
	public function _null(){
		header("HTTP/1.0 404 Not Found");
		$this->error('您访问的页面不存在','/');
		exit;
	}

	//获取机器UV
	protected function _uv(){
		$_from=sget('from','s');
		$_cfrom=cookie::get('_from');
		if(!empty($_from) && empty($_cfrom)){
			cookie::set('_from',$_from);
			$_cfrom=$_from;
		}
		if($_cfrom=='andriod'){
			$this->assign('andriod','y');
		}

		$uv=getUV();
		if(empty($uv)){
			cookie::set('_uv',genUV(),180*86400);
		}
		return $uv;
	}

}
?>
