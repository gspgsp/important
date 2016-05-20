<?php 
/**
 * 订单管理
 */
class pointsAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('points_order');	
		$this->assign('points_status',L('points_status')); //积分订单状态
		$this->assign('express_company',L('express_company'));
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->assign('page_title','积分订单列表');
		$this->display('points.list.html');
	}

	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where= 1;
		$opt=sget('outpu_time','s');//发货时间
		$os=sget('order_status','s');//积分订单状态
		$kt=sget('key_t','s');//第一收货人，快递公司查询
		$kw=sget('key_w','s');

		$k_t=sget('key_type','s');//第二兑换单号，快递单号，收货人手机号码查询
		$k_w=sget('key_word','s');
	
		if (!empty($opt)) $where .=" and `outpu_time` = $opt ";
		if (!empty($os)) $where .=" and `status` = $os ";
		if (!empty($kw)) $where .=" and `$kt` like '%$kw%' ";
		if (!empty($k_w)) $where .=" and `$k_t` = $k_w ";
		//p($where);	
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
	//p($list);die;
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['outpu_time']=$v['outpu_time']>1000 ? date("Y-m-d H:i:s",$v['outpu_time']) : '-';
			$list['data'][$k]['company']=L('express_company')[$v['company']];
			$list['data'][$k]['points_status']=L('points_status')[$v['status']];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}

	/**
	 * 修改积分订单状态
	 * @access public
	 */
	public function checkPoint(){
		$this->is_ajax=true; //指定为Ajax输出
		$yes=sget('yes','i',0);
		$id =sget('id','i');
		if (empty($id)) $this->error('操作有误');
		if ($yes ==1 ) {
			$result=$this->db->where("id = $id")->update("`status`=2");
		}else{
			$result=$this->db->where("id = $id")->update("`status`=4");
		}

		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}


	/**
	 * 保存(新/编辑)产品信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$data = sdata(); //传递的参数		
		$id = $data['id'];
		if(empty($data)) $this->error('错误的请求');
		//发货时间转时间戳
		$data['outpu_time']=strtotime($data['outpu_time']);
		$data['status']=3;
		$result = $this->db->where("id=$id")->update($data+array('create_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
		
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

}