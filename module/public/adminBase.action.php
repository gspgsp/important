<?php
/*
 * 管理后台基类
*/
class adminBaseAction extends action {
	protected $admin_id=0;
	protected $db=NULL;
	protected $debug=FALSE;
	public function __construct() {
		startAdminSession();
		parent::__construct();
		$this->sys=M('system:setting')->getSetting();
		
		$this->cache= E('RedisCluster',APP_LIB.'class');
		$_SESSION['depart']=($this->cache->get('depart_'.SESS_ID)==false?0:$this->cache->get('depart_'.SESS_ID));
		$_SESSION['adminid']=($this->cache->get('adminid_'.SESS_ID)==false?0:$this->cache->get('adminid_'.SESS_ID));
		$_SESSION['name']=($this->cache->get('name_'.SESS_ID)==false?0:$this->cache->get('name_'.SESS_ID));
		$_SESSION['username']=($this->cache->get('username_'.SESS_ID)==false?0:$this->cache->get('username_'.SESS_ID));
		$_SESSION['call_no']=($this->cache->get('call_no_'.SESS_ID)==false?0:$this->cache->get('call_no_'.SESS_ID));
		$_SESSION['call_pwd']=($this->cache->get('call_pwd_'.SESS_ID)==false?0:$this->cache->get('call_pwd_'.SESS_ID));
		$_SESSION['is_super']=($this->cache->get('is_super_'.SESS_ID)==false?0:$this->cache->get('is_super_'.SESS_ID));


		//用户权限检查
  		$this->admin_id = $_SESSION['adminid'];
		$this->db=M('public:common');
		$this->chkPriv();

		$this->_vlog();  //记录用户日志

		//默认为列表页面
		load::L('sys'); //加载语言相
		$this->assign('mini_list',1);
		$this->assign('_today',date("Y-m-d"));
		$this->assign('admin_id',$this->admin_id);
		$this->assign('_first',date("Y-m").'-01');
		$this->assign('_yesterday',date("Y-m-d",time()-86400));
	}

    /**
     * 检查当前用户权限权限
     */
	private function chkPriv(){
		if($this->admin_id<1){
			$this->forward('/index/pass/init');
		}
		if($_SESSION['is_super']>0){ //超级管理员不检查
			return true;
		}
		$this->isAjax(); //检查是否AJax提交

		if(!M('rbac:rbac')->checkAccess($this->admin_id)){ //未通过验证
        	$this->error('您没有权限操作');
		}
	}

    /**
     * 清楚Memcache的某个键值
     */
	protected function clearMCache($key=''){
		if(empty($key)){
			return false;
		}
		$cache=cache::startMemcache();
		$cates=$cache->delete($key);
		return true;
	}

	protected function _vlog(){
		$remark=$_REQUEST;
		unset($remark['m'],$remark['c'],$remark['a']);
		if(!empty($remark)){
			foreach($remark as $k=>$v){ //取前50个字符
				if(strlen($v)>50){
					$remark[$k]=substr($v['v'],0,50);
				}
			}
			$remark=http_build_query($remark);
		}else{
			$remark='';
		}
		//platform
		$platf = '';
		if($this->getBrowser() != 'unknown'){
			$platf = 'web';
		}else{
			$platf = 'unknown';
		}
		//新增日志记录
		$data=array(
			'admin_id'=>$this->admin_id,
			'input_time'=>CORE_TIME,
			'ip'=>get_ip(),
			'device_name'=>$platf,
			'device_version'=>$this->getBrowser().'/'.$this->getBrowserVer(),
			'device_num'=>gethostbyaddr(get_ip()),
			'action'=>'/'.ROUTE_M.'/'.ROUTE_C.'/'.ROUTE_A,
			'remark'=>substr($remark,0,500), //只取500条字符
		);
		$this->db->model('log_admin')->add($data);
	}
	//获取当前用户的审核流
	protected function  _accessChk($flow_id = 0){
		$flow_id +=1;
		if($_SESSION['is_super']>0){ //超级管理员不检查
			return true;
		}
		$flows = M('rbac:rbac')->checkChk($this->admin_id);
		if(in_array($flow_id, $flows)){
			return true;
		}else{
			return false;
		}

	}
	//检测交易员考核目标是否定过，没定目标，不让操作
	public function getReportData(){
		$roleid = M('rbac:rbac')
				->model('adm_role_user as user')
				->leftjoin('adm_role as role','user.role_id = role.id')
				->select('role.pid')
				->where("user.`user_id` = {$_SESSION['adminid']}")
				->getOne();
		if($roleid == 22){   //交易员所在团队id的父id都是22，故写死为22
			$month_date = strtotime(date('Y-m',time())) ;
			$res = $this->db->model('report_user')
				->select('admin_id')
				->where('admin_id = '.$_SESSION['adminid'].' and report_date = "'.$month_date.'"')
				->getOne();
			if(empty($res)){
				$this->error('您当月指标未设置,请先设置当月指标','/user/report/init');
			}
		}
	}
	/**
	 * 获取浏览器类型
	 * @return [type] [description]
	 */
	public function getBrowser(){
	    $agent=$_SERVER["HTTP_USER_AGENT"];
	    if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) //ie11判断
	    return "ie";
	    else if(strpos($agent,'Firefox')!==false)
	    return "firefox";
	    else if(strpos($agent,'Chrome')!==false)
	    return "chrome";
	    else if(strpos($agent,'Opera')!==false)
	    return 'opera';
	    else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false)
	    return 'safari';
	    else
	    return 'unknown';
	}
	/**
	 * 获取浏览器版本
	 * @return [type] [description]
	 */
	public function getBrowserVer(){
	    if (empty($_SERVER['HTTP_USER_AGENT'])){    //当浏览器没有发送访问者的信息的时候
	        return 'unknow';
	    }
	    $agent= $_SERVER['HTTP_USER_AGENT'];
	    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs))
	        return $regs[1];
	    elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs))
	        return $regs[1];
	    elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs))
	        return $regs[1];
	    elseif (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs))
	        return $regs[1];
	    elseif ((strpos($agent,'Chrome')==false)&&preg_match('/Safari\/([^\s]+)/i', $agent, $regs))
	        return $regs[1];
	    else
	        return 'unknow';
	}
}
?>
