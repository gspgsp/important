<?php
/**
 * 客户信息管理
 */
class customershareAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('status',L('status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('identification',L('identification'));//客户级别
		$this->db=M('public:common')->model('customer_pool');
		$this->doact = sget('do','s');
	}

	/**
	 * 会员列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','共享会员列表');
		$this->display('customershare.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','cp.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选	
		// 关键词
		$key_type=sget('key_type','s','c_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='name'){
				$adms = M('rbac:adm')->getIdByName($keyword);
				$where.=" and cp.`customer_manager` in (".join(',',$adms).") ";
			}else if($key_type=='c_name'){
				$cids = M('user:customer')->getcidByCname($keyword);
				$where.=" and cp.`c_id` in (cids  ) ";
			}else{
				$where.=" and cp.$key_type='$keyword' ";
			}
		}
		//筛选能看到的共享客户
		if($_SESSION['adminid'] !=1 && $_SESSION['adminid']>0){
			$where .= " and cp.customer_manager = {$_SESSION['adminid']} ";
		}
		$list=$this->db ->select("cp.*,c.customer_manager as cm, c.c_name, c.chanel,c.need_product,c.legal_person,c.type")->from('customer_pool cp')->leftjoin('customer c','c.c_id = cp.c_id')->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		showtrace();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['cm'] = M('rbac:adm')->getUserByCol($v['cm']);
		 	$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
			$list['data'][$k]['level']=L('company_level')[$v['level']];
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
		}
		$this->assign('isPublic',$this->public);
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * Ajax删除节点s
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->model('customer_pool')->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}	
}