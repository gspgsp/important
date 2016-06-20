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

		//用户权限检查
  		$this->admin_id = $_SESSION['adminid'];
		$this->db=M('public:common');
		$this->chkPriv();

		$this->_vlog();  //记录用户日志

		//默认为列表页面
		load::L('sys'); //加载语言相
		$this->assign('mini_list',1);
		$this->assign('_today',date("Y-m-d"));
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
		
		//新增日志记录
		$data=array(
			'admin_id'=>$this->admin_id,		
			'input_time'=>CORE_TIME,		
			'ip'=>get_ip(),		
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
}
?>
