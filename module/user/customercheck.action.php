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
	 * @return   [type]                   [description]
	 */
	public function  chkstatus($c,$sale,$pur,$status){
		$str='';
		$str .= $c==0 ? '公海客户':'';
		if($c>0 && ($sale==1 && $pur==1 && $status != 9)){
			$str .='已合作客户+已合作供应商';
		}
		elseif($c>0 && ($sale==1 && $pur!=1 && $status != 9)){
			$str .='已合作客户';
		}
		elseif($c>0 && ($sale!=1 && $pur==1 && $status != 9)){
			$str .='已合作供应商';
		}
		elseif($c>0 && ($sale!=1 && $pur!=1 && $status != 9)){
			$str .='私海客户';
		}elseif($c>0 && ($status == 9)){
			$str .='黑名单';
		}
		return $str;
	}

}