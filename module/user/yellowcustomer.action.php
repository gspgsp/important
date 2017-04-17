<?php
/**
 *黄名单客户信息管理
 */
class yellowcustomerAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('status',L('status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('identification',L('identification'));//客户级别
		$this->db=M('public:common')->model('customer');
		$this->doact = sget('do','s');
		$this->public = sget('isPublic','i',0);
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
		$this->assign('page_title','黄名单客户列表');
		$this->display('yellowcustomer.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = '`status` = 8';
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$status = sget("status",'s',''); //状态
		if($status!='') $where.=" and status='$status' ";
		$type = sget("type",'s',''); //状态
		if($type!='') $where.=" and type='$type' ";//type 客户类型
		$level = sget("level",'s',''); //状态
		if($level!='') $where.=" and level='$level' ";//level 客户级别
		$identification = sget("identification",'s',''); //认证
		if($identification!='') $where.=" and identification='$identification' ";
		// 关键词
		$key_type=sget('key_type','s','c_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='c_name'){
				$cidshare = M('user:customer')->getcidByCname($keyword);
				$where.=" and $key_type like '%$keyword%' ";
			}elseif($key_type=='customer_manager'){
				$adms = join(',',M('rbac:adm')->getIdByName($keyword));
				$where.=" and $key_type in ($adms) ";
				$sons = explode(',',M('rbac:rbac')->getSons($_SESSION['adminid']));  //领导
				$pass = in_array($adms,$sons);
				if(!M('rbac:adm')->getIdByName($keyword) && $_SESSION['adminid'] != 1){
					$this->error('<font style="color:red">查询的交易员不存在！</font>');
				}else if(count(M('rbac:adm')->getIdByName($keyword)) > 1 && $_SESSION['adminid'] != 1){
					$this->error('<font style="color:red">暂时不支持模糊查询交易员</font>');
				}else if($_SESSION['adminid'] != $adms && $_SESSION['adminid'] != 1 && 	!$pass){
					$this->error('<font style="color:red">只支持查询自己及下属哦！</font>');
				}
			}elseif($key_type=='need_product'){
				$where.=" and `need_product_adm` like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}
		//筛选自己的客户
		// if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
		// 	$where .= " and `customer_manager` =  {$_SESSION['adminid']} ";
		// }
		$list=$this->db->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
			$list['data'][$k]['level']=L('company_level')[$v['level']];
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
		}
		$this->assign('isPublic',$this->public);
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	//还原黄名单客户为正常客户
	public function restore(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$res = M('user:customer')->getColByName($v,"c_id","c_id");
				if($res>0){
					//还原联系人
					$this->db->model('customer_contact')->where("`c_id`=$v")->update(array('status'=>2));
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('status'=>2));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}