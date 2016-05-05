<?php
/**
 * 产品报价与采购管理
 */
class purchaseAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		//产品信息表
		$this->db=M('public:common')->model('purchase');
		$this->assign('product_type',L('product_type'));//产品分类语言包
		$this->assign('product_status',L('product_status'));//产品状态
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('period',L('period'));//交货周期
		$this->assign('status',L('purchase_status'));//采购状态
		$this->assign('ctype',sget('ctype'));//采购状态
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
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}elseif($action=='submit'){ //获取列表
			$this->_submit();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}elseif($action=='info'){ //获取列表
			$this->_info();exit;
		}
		$this->assign('page_title','采购报价列表');
		$this->display('purchase.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','p.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  ";
		$sTime = sget("sTime",'s','p.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		// 状态
		$status = sget("status",'s',''); 
		if($status!='') $where.=" and p.status='$status' ";
		//品种
		$product_type = sget('product_type','s','');
		if($product_type!='') $where.=" and pd.product_type = '$product_type' ";
		//周期
		$period = sget('period','s','');
		if($period!='') $where.=" and p.period = '$period' ";
		//关键词搜索
		$key_type=sget('key_type','s','p.period');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'f_name'){
				$result = M('product:factory')->getIdsByName($keyword);
				$result = implode($result,',');
				$where.=" and pd.f_id in ($result) ";
			}else{
				$where.=" and `$key_type`='$keyword' ";
			}
			
		}
		$list=$this->db->select("p.*,pd.model, pd.f_id, pd.product_type, pd.process_type, pd.unit")
				->from('purchase p')->join('product pd','pd.id = p.p_id')
				->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['product_type'] = L('product_type')[$v['product_type']];
			$list['data'][$k]['f_id'] = $this->_getFactoryName($v['f_id']);
			$list['data'][$k]['process_type'] = L('process_level')[$v['process_type']];
			$list['data'][$k]['period'] = L('period')[$v['period']];
			if($v['origin']){
				$areaArr = explode('|', $v['origin']);
				$list['data'][$k]['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
			}

		}

		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}	
	/**
	 * Ajax删除节点s
	 * @access private 
	 */
	private function _remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	private function _save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		foreach($data as $k=>$v){
			$_data=array(
				'cities'=>$v['cities'],
				'update_time'=>CORE_TIME,
				'admin_name'=>$_SESSION['name'],
			);
			if(isset($v['_state']) && $v['_state']=='added'){
				$sql[]=$this->db->addSql($_data+array(
					'input_time'=>CORE_TIME,
				));
			}else{
				$sql[]=$this->db->wherePk($v['id'])->updateSql(array('status'=>$v['status']));
			}
			
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	/**
	 * 获取处理订单
	 * @access private 
	 * @return html
	 */
	private function _info(){
		$this->is_ajax=true;
		$id=sget('id','i');
		if($id>0){
			$info=$this->db->model('ship_collect')->wherePk($id)->getRow();
		}
		//分配物流公司
		$this->assign('info',$info);
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('page_title','手动添加采购信息');
		$this->display('purchase.add.html');
	}
	/**
	 * 提交订单详细信息
	 */
	public function submit(){
		$id=sget('id','i',0); //ID
		$_info=sget('info','a');
		if(empty($_info)){
			$this->error('操作有误');	
		}
		$_info['admin_name']=$_SESSION['name'];
		$_info['update_time']=CORE_TIME;
		$_info['status']=1;
		$_data=saddslashes($_info);
		$this->db->model('ship_collect')->wherePk($id)->update($_data);
		$this->success('操作成功');
	}

	/**
	 * 新增采购信息
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$utype = $data['ctype'];
		//组合区域
		$data['origin']= $data['company_province'].'|'.$data['company_city'];
		if($data['company_province']>0) $data['area'] = M('system:region')->get_area($data['company_province']);
		
		if($utype==1){

		}else{

		}
		$result=$this->db->add($data);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$this->success('操作成功');
	}
	/**
	 * 根据厂家id获取厂家名称
	 */
	private function _getFactoryName($id){
		$result  = $this->db->model('factory')->select('f_name')->where("`fid` = $id")->getOne();
		return empty($result) ? '-' : $result;
	}
}