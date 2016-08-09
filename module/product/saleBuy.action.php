<?php
/**
 * 销售采购管理
 */
class saleBuyAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		//销售采购报价表
		$this->db=M('public:common')->model('sale_buy');
		$this->assign('product_type',L('product_type'));//产品分类语言包
		$this->assign('product_status',L('product_status'));//产品状态
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('period',L('period'));//交货周期
		$this->assign('status',L('purchase_status'));//采购状态
		$this->assign('ship_type',L('ship_type'));//运输类型
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
		$this->assign('slt','slt');
		$this->assign('ctype','1');
		$this->assign('page_title','采购报价列表');
		$this->display('salebuy.list.html');
	}
	/**
	 *现货采购
	 */
	public function cargo(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('ctype','2');
		$this->assign('page_title','采购报价列表');
		$this->display('salebuy.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$slt = sget("do",'s','');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','sb.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and p.`type` = 1 ";   //1 采购
		//列表筛选
		$id = sget('id','i',0);
		if($id>0){
			$where .= " and sb.p_id = $id ";
		}
		//运输方式
		$ship_type = sget('ship_type','i',0);
		if($ship_type != ''){
			$where .= " and sb.ship_type = $ship_type ";
		}
		$sTime = sget("sTime",'s','sb.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		// 状态
		$status = sget("status",'s',''); 
		if($status!='') $where.=" and sb.status='$status' ";
		//关键词搜索
		$key_type=sget('key_type','s','sb.sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'saleid' || $key_type == 'buyid'){
				$result = M('user:customer')->getInfoByCname('c_name',$keyword);
				$result = implode($result,',');
				if($key_type == 'saleid'){
					$where.=" and sb.c_id in ($result) ";
				}else{
					$where.=" and p.c_id in ($result) ";
				}
				
			}else{
				$where.=" and $key_type='$keyword' ";
			}
			
		}
		$list=$this->db->select("p.id as purchaseid, pd.model, pd.f_id, p.number as pnumber, p.type as ptype, p.unit_price as pprice,p.c_id as pcid, p.cargo_type, p.origin, sb.*")->from('sale_buy sb')->leftjoin('purchase p','p.id = sb.p_id')
				->leftjoin('product pd','pd.id = p.p_id')
				->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i",$v['update_time']) : '-';
			$list['data'][$k]['expiry_date']=$v['expiry_date']>1000 ? date("Y-m-d",$v['expiry_date']) : '-';
			$list['data'][$k]['delivery_date']=$v['delivery_date']>1000 ? date("Y-m-d",$v['delivery_date']) : '-';
			$list['data'][$k]['model'] = $v['f_id']>0 ? M('product:factory')->getFnameById($v['f_id']).'|'.$v['model'] : $v['model'];
			$list['data'][$k]['pcid'] = $v['ptype'] == 2 ? M('user:customer')->getColByName($v['pcid']) : M('user:customer')->getColByName($v['c_id']); //采购的厂家
			$list['data'][$k]['pcid_id'] = $v['pcid']; 
			$list['data'][$k]['c_id'] = $v['ptype'] == 2 ? M('user:customer')->getColByName($v['c_id']) : M('user:customer')->getColByName($v['pcid']); 
			$list['data'][$k]['pn'] = $v['ptype'] == 1 ? $v['pprice'].'|'.$v['pnumber'] : $v['price'].'|'.$v['number'];
			$list['data'][$k]['sbn'] = $v['ptype'] == 1  ? $v['price'].'|'.$v['number']  : $v['pprice'].'|'.$v['pnumber'];
			$list['data'][$k]['ship'] = $v['ship_type'];
			$list['data'][$k]['cargo_type'] = L('cargo_type')[$v['cargo_type']];
			$list['data'][$k]['ship_type'] = L('ship_type')[$v['ship_type']];
			if($v['origin']){
				$areaArr = explode('|', $v['origin']);
				$list['data'][$k]['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
			}

		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
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
				'update_time'=>CORE_TIME,
				'chk_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
			);
			if(isset($v['_state']) && $v['_state']=='added'){
				$sql[]=$this->db->addSql($_data+array(
					'input_time'=>CORE_TIME,
				));
			}else{
				$sql[]=$this->db->wherePk($v['id'])->updateSql(array('status'=>$v['status'])+$_data);
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
			$info=$this->db->wherePk($id)->getRow();
			//获取客户名称
			$c_name = M('user:customer')->getColByName($info['c_id']);
			// 获取联系人名字
			$username = M('user:customerContact')->getColByName($info['user_id']);
			$this->assign('data',$info);
			$this->assign('c_name',$c_name);
			$this->assign('username',$username);
		}
		$this->assign('id',$id);
		$this->assign('ship_type',L('ship_type'));
		$this->assign('page_title','手动添加报价');
		$this->display('salebuy.add.html');
	}
	/**
	 * 获得订单详情并审核
	 */
	public function view(){
		$this->is_ajax=true;
		$pid=sget('id','i'); //报价ID
		// 根据采购id获得报价id
		$id = M('product:purchase')->getColById($pid,'last_buy_sale','id'); //获得报价表的ID
		//获取采购订单的区域
		$provinces = M('product:purchase')->getColById($pid,'provinces','id'); //获得报价表的ID
		$info=M('product:salebuy')->getSalebuyInfo($id);
		//获取订单信息
		$orderinfo = M('product:unionOrder')->getAllInfoById($id,'p_sale_id');
		if(empty($info)) $this->error('错误的数据请求');
		$info['saleminfo'] =M('rbac:adm')->getUserInfoById($info['customer_manager']); //卖交易员信息
		$info['buyminfo'] =M('rbac:adm')->getUserInfoById($info['ptraderid']);//买交易员信息
		$info['product']=M('product:product')->getFnameByPid($info['pp_id']);//商品信息
		//获取卖家公司信息
		$info['salecinfo'] = M('user:customer')->getCinfoById($info['c_id']);
		//获取买家公司信息
		$info['buycinfo'] = M('user:customer')->getCinfoById($info['pc_id']);
		// 处理交货地区信息
		$info['porigin'] = M('system:region')->get_name($provinces);
		//处理配货地点
		$info['delivery_place'] = M('system:region')->get_name($info['delivery_place']);
		$this->assign('info',$info);
		$this->assign('m_transport',L('transport_type')); //运输方式
		$this->assign('pay_method',L('pay_method'));
		$this->assign('orderinfo',$orderinfo);
		$this->assign('id',$id);//报价表
		$this->assign('pid',$pid); //采购表id
		$this->assign('ship_type',L('ship_type'));
		$this->assign('page_title','报价审核页面');
		$this->display('salebuy.view.html');
	}
	/**
	 * 获得订单详情并审核
	 */
	public function chk(){
		$this->is_ajax=true;
		$id=sget('id','i');  //采购id
		$info = M('product:purchase')->getPurchaseInfo($id); //报价信息pruchse表
		if(empty($info)) $this->error('错误的数据请求');
		$info['cminfo'] =M('rbac:adm')->getUserInfoById($info['customer_manager']); //采购交易员姓名
		$info['buycinfo'] = M('user:customer')->getCinfoById($info['c_id']); //采购厂家信息
		$info['originarea'] = $info['origin'];
		// 处理地区信息
		$info['origin'] = M('system:region')->get_chinese_area($info['origin']);
		$info['provinces']= M('system:region')->get_name($info['provinces']);
		$info['user_info'] = M('user:customerContact')->getListByUserid($info['user_id']);
		// p($info);die;
		$this->assign('info',$info);
		$this->assign('product_status',L('product_status'));
		$this->assign('m_transport',L('transport_type')); //运输方式
		$this->assign('pay_method',L('pay_method'));
		$this->assign('id',$id); //采购id
		$this->assign('ship_type',L('ship_type'));
		$this->assign('page_title','报价审核页面');
		$this->display('salebuy.chk.html');
	}
	/**
	 * 新增采购信息
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$id =  $data['id'];
		//公共数据
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'sn'=>genOrderSn(),
			'expiry_date' => strtotime($data['expiry_date']),
			'delivery_date' => strtotime($data['delivery_date']),
			'customer_manager'=>$_SESSION['adminid'],
		);
		//买房与买方不能相同 
		if($data['c_id'] == $this->_getCidByPurseId($data['p_id'])){
			$this->error('卖方与买方不能相同！');
		}
		//数据添加操作
		if($data['id']>0){
			if($this->db->model('sale_buy')->where("id = $id")->update(array('update_time'=>CORE_TIME,'update_admin'=>$_SESSION['name'],'expiry_date' => strtotime($data['expiry_date']),
			'delivery_date' => strtotime($data['delivery_date']),)+$data)) $this->success('操作成功');
		
		}else{
			// 手动添加报价
			$this->db->startTrans();
			$this->db->model('sale_buy')->add($_data+$data);
			$this->db->model('purchase')->where('id='.$data['p_id'])->update(array('supply_count'=>"+=1"));
			if($this->db->commit()){
				 $this->success('操作成功');	
			}else{
				$this->db->rollback();
				$this->error('操作失败');
			}

			
		}
		$this->error('添加失败');	
	}
	/**
	 * 根据报价id获取客户id
	 */
	private function _getCidByPurseId($id){
		$result  = $this->db->model('purchase')->select('c_id')->where("`id` = $id")->getOne();
		return empty($result) ? 0 : $result;
	}
	/**
	 * 提交订单审核
	 */
	public function chkSubmit(){
		$this->is_ajax = true;
		$data = sdata();
		$id = $data['id'];  //purchase 的 id
		$p_id = $data['p_id'];   //purchase 的商品 id
		$pstatus = M('product:purchase')->getInfoById($id); //采购id
		//采购状态判断
		if($pstatus['status'] !=2){
			$this->error('采购订单状态错误！');  //采购订单判断
		}
		//报价状态
		$salebuyinfo = M('product:salebuy')->getSalebuyInfoById($data['m_supplier']); //采购id
		if($salebuyinfo['status'] != 2){
			$this->error('报价订单状态错误！');
		}
		//查询报价表信息(报价客户id)
		// $salebuy_cid = M('product:salebuy')->getColById($data['m_supplier'],'c_id');
		//查询报价的地址
		
		// $salebuy_num = M('product:salebuy')->getColById($data['m_supplier']);// 查询报价数量
		$total_price = $data['m_price']*$data['deal_num'];		// 计算商品总价
		// 开始事物
		$this->db->startTrans();
		// 创建审核过的订单
		$_data = array(
			'order_name'=>'报价ID为【'.$data['m_supplier'].'】采购ID为【'.$id.'】',
			'order_sn'=>genOrderSn(),
			'order_source'=>1,
			'sale_id'=>$salebuyinfo['c_id'],  //报价客户id
			'buy_id'=>$data['pc_id'],  //采购客户id
			'p_buy_id'=>$id,
			'p_sale_id'=>$data['m_supplier'],
			'buy_user_id'=>M('product:unionOrder')->getPcol("$id"),
			'sale_user_id'=>M('product:unionOrder')->getScol($data['m_supplier']),
			'sign_time'=>CORE_TIME,
			'deal_num'=>$data['deal_num'],
			'total_price'=>$total_price,
			'deal_price'=>$data['m_price'],
			'pay_method'=>$data['pay_method'],
			'remark'=>$data['m_remark'],
			'customer_manager'=>$_SESSION['adminid'],
			'sign_place'=>'网站签约',//签约地点
			'pickup_location'=>$salebuyinfo['delivery_place'],//提货地点
			'delivery_location'=>$pstatus['provinces'], //送货地点
			'transport_type'=>$data['m_transport'], //运输方式
			'pickup_time'=>strtotime($data['pickup_time']),
			'delivery_time'=>strtotime($data['delivery_time']),
			'status'=>$data['status'],
		);
		$this->db->model('union_order')->add($_data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],));  
		$o_id = $this->db->getLastID();
		//更新其他报价单状态(如果报价人数超过一个人才进行此操作)
		if($pstatus['supply_count']>1) $this->db->model('sale_buy')->where("`p_id` = $id and `id` != {$data['m_supplier']}")->update(array('status'=>8,'update_time'=>CORE_TIME,)); 
		// //更新自身报价为审核通过
		$this->db->model('sale_buy')->where("`id` = {$data['m_supplier']}")->update(array('status'=>3,'update_time'=>CORE_TIME,));
		//更新采购单状态
		$this->db->model('purchase')->where("`id` = $id")->update(array('last_buy_sale'=>$data['m_supplier'],'update_time'=>CORE_TIME,'update_admin'=>$_SESSION['name'],'status'=>3,));
		$product = array(
			'p_id'=> $data['p_id'],  //采购商品id
			'o_id'=>$o_id,
			'number'=>$data['deal_num'],
			'unit_price'=>$data['m_price'],
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		//创建订单详情
		$this->db->model('union_order_detail')->add($product);
		// showtrace();
		if($this->db->commit()){
			$this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('操作失败');
		}
	}

}