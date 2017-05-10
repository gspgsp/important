<?php
/**
 * 客户信息管理
 */
class customercheckAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer');
		// $this->doact = sget('do','s');
		// $this->public = sget('isPublic','i',0);
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
		$this->assign('page_title','公司列表');
		$this->display('customer.check.html');
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

		$where = '1';
		// 关键词
		$c_name=sget('c_name','s','c_name');
		if(!empty($c_name)){
			$where.=" and c_name like'%$c_name%' ";
			$list=$this->db
			->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
				$list['data'][$k]['admin_id'] = $v['customer_manager'];
				$list['data'][$k]['status']=$this->chkstatus($v['customer_manager'],$v['is_sale'],$v['is_pur'],$v['status']);
				$list['data'][$k]['type']=L('company_type')[$v['type']];// 客户类型
				$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
				// $list['data'][$k]['depart']=C('depart')[$v['depart']];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		}else{
			$result='无公司名不能查询';
		}
		$this->json_output($result);
	}
	/**
	 * [chkstatus description]
	 * @Author   xianghui
	 * @DateTime 2017-02-06T14:00:57+0800
	 * @return   [type]  [description]
	 */
	public function  chkstatus($c,$sale,$pur,$status){
		$str='';
		$str .= ($c==0 && $status != 4) ? '公海客户':'';
		if($c>0 && ($sale==1 && $pur==1 && $status != 9 && $status != 8)){
			$str .='已合作客户+已合作供应商';
		}
		elseif($c>0 && ($sale==1 && $pur!=1 && $status != 9 && $status != 8)){
			$str .='已合作客户';
		}
		elseif($c>0 && ($sale!=1 && $pur==1 && $status != 9 && $status != 8)){
			$str .='已合作供应商';
		}
		elseif($c>0 && ($sale==2 || $pur==2 || $status == 10)){
			$str .='黑名单待审核';
		}
		elseif($status == 4){
			$str .='已作废客户';
		}
		elseif($c>0 && ($sale!=1 && $pur!=1 && $status != 9 && $status != 8)){
			$str .='私海客户';
		}elseif($c>0 && ($status == 9)){
			$str .='黑名单';
		}elseif($c>0 && ($status == 8)){
			$str .='黄名单';
		}
		return $str;
	}
	public function share_apply(){
		$c_id=sget('c_id','i',0);
		$apply_to_uid=sget('apply_to_uid','i',0);
		if(empty($c_id) || empty($apply_to_uid)) $this->error('信息错误');
		//先查 是否申请过
		$where = "apply_uid = ".$_SESSION['adminid']." and c_id = ".$c_id." and apply_to_uid = ".$apply_to_uid;
		$find_res = $this->db->model('customer_share_apply')->select('id')->where($where)->getOne();
		if($find_res){
			$this->error('申请已发出，请联系相关业务员处理');
		}
		$user_res = $this->db->model('admin')->select('admin_id')->where("admin_id = {$apply_to_uid} and status = 0")->getOne();
		if($res){
			$this->error('该业务员已离职，请联系总监处理');
		}
		$data['c_id'] = $c_id;
		$data['apply_to_uid'] = $apply_to_uid;
		$data['apply_uid'] = $_SESSION['adminid'];
		$data['input_time'] = CORE_TIME;
		$add_res = $this->db->model('customer_share_apply')->add($data);
		if($add_res){
			$this->success('申请成功');
		}else{
			$this->error('申请失败');
		}
	}

}