<?php
/**
 * 销售采购管理
 */
class buySaleAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		//销售采购报价表
		$this->db=M('public:common')->model('sale_buy');
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
		$purid = sget('purid','i',0);
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
		$this->assign('purid',$purid);
		$this->assign('slt','slt');
		$this->assign('ctype','2');
		$this->assign('page_title','采购报价列表');
		$this->display('buysale.list.html');
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
		$where=" 1  and p.`type` = 2 ";   //2报价
		//列表筛选
		$id = sget('id','i',0);
		if($id>0){
			$where .= " and sb.p_id = $id ";
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
		$list=$this->db->select("p.id as purchaseid, pd.model, pd.f_id, p.number as pnumber, p.unit_price as pprice,p.c_id as pcid, p.cargo_type, p.origin, sb.*")->from('sale_buy sb')->leftjoin('purchase p','p.id = sb.p_id')
				->leftjoin('product pd','pd.id = p.p_id')
				->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		// showtrace();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i",$v['update_time']) : '-';
			$list['data'][$k]['expiry_date']=$v['expiry_date']>1000 ? date("Y-m-d",$v['expiry_date']) : '-';
			$list['data'][$k]['delivery_date']=$v['delivery_date']>1000 ? date("Y-m-d",$v['delivery_date']) : '-';
			$list['data'][$k]['model'] = $v['f_id']>0 ? M('product:factory')->getFnameById($v['f_id']).'|'.$v['model'] : $v['model'];
			$list['data'][$k]['pcid'] = M('user:customer')->getColByName($v['pcid']); //采购的厂家
			$list['data'][$k]['pcid_id'] = $v['pcid']; 
			$list['data'][$k]['c_id'] = M('user:customer')->getColByName($v['c_id']); //报价的厂家
			$list['data'][$k]['pn'] = $v['pprice'].'|'.$v['pnumber'];
			$list['data'][$k]['sbn'] = $v['price'].'|'.$v['number'];
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
		$this->display('buysale.add.html');
	}
	/**
	 * 获得订单详情并审核
	 */
	public function view(){
		$this->is_ajax=true;
		$chk = sget('chk','i',0);
		$pid=sget('id','i'); //报价ID(销售表中的id)
		// 根据采购id获得报价id
		$info=M('product:salebuy')->getSalebuyInfo($pid);
		if(empty($info)) $this->error('错误的数据请求');
		$info['saleminfo'] =M('rbac:adm')->getUserInfoById($info['customer_manager']); //卖交易员信息
		$info['buyminfo'] =M('rbac:adm')->getUserInfoById($info['ptraderid']);//买交易员信息
		$info['product']=M('product:product')->getFnameByPid($info['pp_id']);//商品信息
		//获取卖家公司信息
		$info['salecinfo'] = M('user:customer')->getCinfoById($info['c_id']);
		//获取买家公司信息
		$info['buycinfo'] = M('user:customer')->getCinfoById($info['pc_id']);
		$info['originarea'] = $info['porigin'];   //区域
		// 处理地区信息
		$info['porigin'] = M('system:region')->get_chinese_area($info['porigin']);
		$this->assign('info',$info);
		$this->assign('m_transport',L('transport_type')); //运输方式
		$this->assign('pay_method',L('pay_method'));
		$this->assign('id',$info['purchaseid']);//（pruchare 的主键id）
		$this->assign('pid',$pid); //（salebuy主键）
		$this->assign('chk',$chk);
		$this->assign('ship_type',L('ship_type'));
		$this->assign('page_title','报价审核页面');
		$this->display('buysale.view.html');
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
		$p_id = $data['p_id'];   //salebuy的id
		$product_id = $data['pp_id'];   //purchase 的商品 id
		$pstatus = M('product:purchase')->getColById($id,'status','id'); //销售订单状态
		//销售订单状态判断
		if($pstatus !=2){
			$this->error('销售订单状态错误！');  //采购订单判断
		}
		//采购订单状态判断
		if($data['status'] != 1){
			$this->error('采购订单状态错误！');
		}
		//查询销售订单剩余数量是否合法
		$salednum = intval(M('product:salebuy')->getCount($id)); //销售掉数量
		if((intval($data['pnumber']) - $salednum) > intval($data['number'])) $this->error('采购数量大于可销售数量，请检查！');
		$buy_num = M('product:salebuy')->getColById($p_id); // 查询报价数量
		$total_price = $data['m_price']* $buy_num;	    // 计算商品总价
		// 开始事物
		$this->db->startTrans();
		// 创建审核过的订单
		$_data = array(
			'order_name'=>'采购ID为【'.$p_id.'】销售ID为【'.$id.'】',
			'order_sn'=>genOrderSn(),
			'order_source'=>1,
			'sale_id'=>$data['c_id'],  //采购客户id
			'buy_id'=>$data['pc_id'],  //销售客户id
			'p_buy_id'=>$id,  //销售id
			'p_sale_id'=>$p_id, //采购id
			'buy_user_id'=>M('product:unionOrder')->getPcol($id),
			'sale_user_id'=>M('product:unionOrder')->getScol($p_id),
			'sign_time'=>CORE_TIME,
			'total_price'=>$total_price,
			'deal_price'=>$data['m_price'],
			'pay_method'=>$data['pay_method'],
			'remark'=>$data['m_remark'],
			'customer_manager'=>$_SESSION['adminid'],
			'sign_place'=>'网站签约',//签约地点
			'pickup_location'=>$data['originarea'],//提货地点
			'delivery_location'=>$data['pstore_house'], //送货地点
			'transport_type'=>$data['m_transport'], //运输方式
			'pickup_time'=>strtotime($data['pickup_time']),
			'delivery_time'=>strtotime($data['delivery_time']),
			'status'=>1,
		);
		//创建订单
		$this->db->model('union_order')->add($_data+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],));  
		$o_id = $this->db->getLastID();
		$product = array(
			'p_id'=> $product_id,  //采购商品id
			'o_id'=>$o_id,
			'number'=>$buy_num,
			'unit_price'=>$data['m_price'],
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		//创建子订单详情
		$this->db->model('union_order_detail')->add($product);
		//更新自身采购为审核通过
		$this->db->model('sale_buy')->where("`id` = $p_id")->update(array('status'=>2));
		if($this->db->commit()){
			$this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('操作失败');
		}
	}

}