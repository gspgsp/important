<?php 
/**
 * 仓库出库明细
 */
class storeOutLogAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('out_log');
		$this->doact = sget('do','s');
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
		$this->assign('page_title','订单仓库出库明细');
		$this->display('outlog.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where =" 1 and `del` = 0 ";
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','store_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='store_id'  ){
			$keyword=M('product:store')->getSidBySname($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='store_aid'){
			$keyword=M('product:store_admin')->getSaidByName($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='p_id' ){
			$keyword=M('product:product')->getpidByPname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['out_time']=$v['out_time']>1000 ? date("Y-m-d H:i:s",$v['out_time']) : '-';
			$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
			$list['data'][$k]['model']=strtoupper(M("product:product")->getModelById($v['p_id']));//获取牌号
			$list['data'][$k]['fname']=M("product:product")->getFnameByid($v['p_id']);//获取厂家
			$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($v['store_aid']); //获得入库人姓名
			$list['data'][$k]['sales_type']=L('sales_type')[M("product:order")->getColByName($v['o_id'],'sales_type')];//出库流水
			$list['data'][$k]['order_sn']=M("product:order")->getColByName($v['o_id'],'order_sn');//订单id
			$list['data'][$k]['out_storage_status']=L('out_storage_status')[$v['out_storage_status']];
			$list['data'][$k]['cname'] = M("product:order")->getCnameByOid($v['o_id']);
			$list['data'][$k]['ship_time']=$v['ship_time']>1000 ? date("Y-m-d H:i:s",$v['ship_time']) : '-';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 出库详细流水
	 */
	public function logDetail(){
		$doact=sget('do','s');
		$id=sget('id','i',0);
		$this->assign('id',$id);//查寻明细下所有入库动作
		$action=sget('action','s');
		$this->assign('page_title','订单管理列表');
		if($action=='grid'){ //获取列表
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			//筛选
			$where = $id >0 ? " `inlog_id` = $id " : " 1 ";
			//筛选时间
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			//关键词搜索
			$key_type=sget('key_type','s','store_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword) && $key_type=='store_id'  ){
				$keyword=M('product:store')->getSidBySname($keyword);
				$where.=" and `$key_type` in ('$keyword') ";
			}elseif(!empty($keyword) && $key_type=='store_aid'){
				$keyword=M('product:store_admin')->getSaidByName($keyword);
				$where.=" and `$key_type` in ('$keyword') ";
			}elseif(!empty($keyword) && $key_type=='p_id' ){
				$keyword=M('product:product')->getpidByPname($keyword);
				$where.=" and `$key_type` in ($keyword) ";
			}
			$list=$this->db->model('out_logs')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
				$list['data'][$k]['model']=M("product:product")->getModelById($v['p_id']);//获取牌号
				$list['data'][$k]['f_name']=M("product:product")->getFnameByid($v['p_id']);//获取厂家
				$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($v['store_aid']); //获得入库人姓名
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}else{
			$this->display('outLogDetial.list.html');
		}
	}
	/**
	 * 撤销出库详细流水
	 */
	public function outstoreBack(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		p($ids);
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$vas = explode(',', $ids);
		$this->db->startTrans();
	}



	/**
	 * Ajax删除
	 * @access private 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$list = $this->db->where("id in ($ids)")->update(array('del'=>1));
		$this->success('操作成功');
	}
	/**
	 * 保存发货信息
	 */
	public function shipSave(){
		$this->is_ajax = true;
		$data = sdata();
		$id = $data['id'];
		if(empty($id)) $this->error('操作有误！');
		$data['ship_time'] = strtotime($data['ship_time']);
		$result = $this->db->where("id = $id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
		if($result) $this->success('操作成功！');
		$this->error('操作失败');
	}
}