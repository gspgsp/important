<?php
/**
 * 风控准入
 */
class riskinAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('status',L('status'));// 客户状态
		$this->assign('type',L('company_type'));//客户类型
		$this->assign('riskin_status',L('riskin_status')); //申请处理状态
		$this->assign('product_type',L('product_type')); //产品类别

		$this->db=M('public:common')->model('finance_customer');
		$this->doact = sget('do','s');
	}

	/**
	 * 客户准入列表
	 * @access public 
	 * @return html
	 */
	public function customer(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','客户准入列表');
		$this->display('riskcustomerin.list.html');
	}
	/**
	 * 商品准入列表
	 * @access public 
	 * @return html
	 */
	public function product(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid2();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','商品准入列表');
		$this->display('riskproductin.list.html');
	}
	/**
	 * 仓库准入列表
	 * @access public 
	 * @return html
	 */
	public function store(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid3();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','仓库准入列表');
		$this->display('riskstorein.list.html');
	}
	/**
	 * 处理产品准入
	 * @access private 
	 * @return html
	 */
	public function _grid2(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
 		$product_type = sget("product_type",'s'); //产品类型
		$product_type_id = array_search($product_type, L('product_type'));
		if($product_type){
			if($product_type_id==''){
				$where.=" and pro.product_type=-1";	
			}else{
				$where.=" and pro.product_type=".$product_type_id;
			}
		}
		$model = sget("model",'s'); //产品牌号
		if($model!='') $where.=" and pro.model='".$model."'";
		$status = sget("status",'i'); //审核状态
		if($status!='') $where.=" and pro_in.status=".$status;
		$list=$this->db->model('finance_product_in pro_in')
					->select('pro_in.*,pro.model,pro.product_type,fac.f_name')
					->leftjoin('product pro','pro.id=pro_in.p_id')
					->leftjoin('factory fac','fac.fid=pro.f_id')
					->where($where)->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
					// showtrace();
			foreach($list['data'] as $k=>$v){
			$list['data'][$k]['product_type']=L('product_type')[$v['product_type']];
			$list['data'][$k]['status']=$v['status']==1?'允许':'不允许';
			$list['data'][$k]['create_user']= M('rbac:adm')->getUserByCol($v['create_user']);
			$list['data'][$k]['modify_user']= M('rbac:adm')->getUserByCol($v['modify_user']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * 处理客户准入
	 * @access private 
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$sTime = sget("sTime",'s','create_date'); //搜索时间类型
		$where.=getTimeFilterByDateTime($sTime); //时间筛选
		$c_name = sget("c_name",'s',''); //公司名
 		$cname_str = M('user:customer')->getLikeCidByCname($c_name);
 		if($c_name!='') $where.=' and fcus.c_id in ('.$cname_str.')';
 		$company_type = sget("company_type",'i'); //客户类型
		if($company_type!='') $where.=" and cus.type=".$company_type;
		$status = sget("dispose_status",'i'); //审核状态
		if($status!='') $where.=" and fcus.status=".$status;
		$list=$this->db->model('finance_customer fcus')
					->select('fcus.*,cus.status as c_status,cus.type,cus.c_name')
					->leftjoin('customer cus','cus.c_id=fcus.c_id')
					->where($where)->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		// p($list);die;
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['c_name']=$v['c_name'];
			// $list['data'][$k]['contact_id']=$v['contact_id'];
			$list['data'][$k]['create_date']=$v['create_date'];
			$list['data'][$k]['modify_date']=$v['modify_date'];
			$list['data'][$k]['status']=L('riskin_status')[$v['status']];
			$list['data'][$k]['company_type']=L('company_type')[$v['type']];
			$list['data'][$k]['cus_status']=L('status')[$v['c_status']];
			// $list['data'][$k]['create_user']= M('rbac:adm')->getUserByCol($v['create_user']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * 处理仓库准入
	 * @access private 
	 * @return html
	 */
	public function _grid3(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$sTime = sget("sTime",'s','create_date'); //搜索时间类型
		$where.=getTimeFilterByDateTime($sTime); //时间筛选
		$store_name = sget("store_name",'s',''); //仓库名
 		if($store_name!='') $where.=' and s.store_name = "'.$store_name.'"';
		$status = sget("status",'i'); //审核状态
		if($status!='') $where.=" and si.status=".$status;
		$list=$this->db->model('finance_store_in si')
					->select('si.*,s.`store_name`,s.`store_address`')
					->leftjoin('store s','s.id=si.store_id')
					->where($where)->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
					// showtrace();
		// p($list);die;
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['status']=$v['status']==1?'已启用':'已禁用';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * 风控要点
	 * @Author   yezhongbao
	 * @DateTime 2016-10-24T16:00:33+0800
	 * @return   [type]                   [description]
	 */
	public function riskPoints(){
		$user_id=sget('id','i');
		$res=M('user:customer')->getPk($user_id); //查询公司信息
		// p($res);die;
		$info['c_name'] = $res['c_name'];
		$info['input_time'] = date('Y-m-d H:i:s',$res['input_time']);
		$info['register_length'] = floor((time()-$res['input_time'])/86400)."天";
		$orderRes = M('product:order')->getOrderInfoByCid($user_id);
		// $info['sale_order_num'] = $res['c_name'];
		// $info['buy_order_num'] = $res['c_name'];
		// p($orderRes);die;
		foreach ($orderRes as $key => $value) {
			if($value['order_type'] == 1){
				$sale_price = $value['total_price'];
				$sale_num = $value['num'];
				$sale_time = $value['input_time'];
			}else{
				$buy_price = $value['total_price'];
				$buy_num = $value['num'];
				$buy_time = $value['input_time'];
			}
		}
		$info['sale_num'] = empty($sale_num)?'0':$sale_num;
		$info['buy_num'] = empty($buy_num)?'0':$buy_num;
		$info['sale_price'] = empty($sale_price)?'0':$sale_price;
		$info['buy_price'] = empty($buy_price)?'0':$buy_price;
		if(empty($sale_time) && empty($buy_time)){
			$info['last_time'] = '暂无交易时间';
		}else{
			$info['last_time'] = $sale_time>$buy_time?date('Y-m-d H:i:s',$sale_time):date('Y-m-d H:i:s',$buy_time);
		}
		$info['total_num'] = $info['sale_num'] + $info['buy_num'];
		$info['total_price'] = ($info['sale_price'] + $info['buy_price'])/10000;
		$info['sale_price'] = $info['sale_price']/10000;
		$info['buy_price'] = $info['buy_price']/10000;
		$info['registered_capital'] = $res['registered_capital'];
		$this->assign('info',$info);
		$this->display('customer.riskPoints.html');
	}
	/**
	 * 新增客户
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');
		/**----------------处理准入客户--------------------**/
		//查询准入列表，如果存在该客户，则不添加
		if(!empty($data['c_id'])){
			$c_id = $this->db->model('finance_customer')->select('c_id')->where('c_id='.$data['c_id'])->getOne();
			if($c_id){
				$this->error('该客户已在准入列表中，无需再次添加');
			}
			$data['status']=1;
			$data['create_date']=date('Y-m-d H:i:s',time());
			$data['create_user']=$_SESSION['adminid'];
			$res = $this->db->model('finance_customer')->add($data);
			if($res){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
		/**----------------处理准入商品--------------------**/
		if (!empty($data['p_id'])) {
			//查询准入列表，如果存在该商品，则不添加
			$p_id = $this->db->model('finance_product_in')->select('p_id')->where('p_id='.$data['p_id'])->getOne();
			if($p_id){
				$this->error('该商品已在准入列表中，无需再次添加');
			}
			$data['status']=1;
			$data['create_date']=date('Y-m-d H:i:s',time());
			$data['create_user']=$_SESSION['adminid'];
			$res = $this->db->model('finance_product_in')->add($data);
			if($res){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
		/**----------------处理准入仓库--------------------**/
		if (!empty($data['store_id'])) {
			//查询准入列表，如果存在该商品，则不添加
			$store_id = $this->db->model('finance_store_in')->select('store_id')->where('store_id='.$data['store_id'])->getOne();
			if($store_id){
				$this->error('该商品已在准入列表中，无需再次添加');
			}
			$data['status']=1;
			$data['create_date']=date('Y-m-d H:i:s',time());
			$data['create_user']=$_SESSION['username'];
			// p($data);die;
			$res = $this->db->model('finance_store_in')->add($data);
			if($res){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
	}
	/**
	 * 层级审核
	 */
	public function verify(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = !empty(sdata())?sdata():$_POST;
		if(empty($data)) $this->error('错误的操作');
		switch ($data['ctype']) {
			case '1':
				$status = '2';
				break;
			case '2':
				$status = '3';
				break;
			case '3':
				$status = '4';
				break;
			case '5':
				$status = '5';
				break;
		}
		$_data=array(
			'status'=>$status,
			'remark'=>$data['remark'],
			'limit'=>$data['limit'],
			'modify_date'=>date('Y-m-d H:i:s',CORE_TIME),
			'modify_user'=>$_SESSION['adminid'],
		);
		$res = $this->db->model('finance_customer')->where('id = '.$data['id'])->update($_data);
		$sp_info = array(
			'c_id' => $data['c_id'],
			'approver' => $_SESSION['adminid'],
			'status' => $status,
			'content' => $data['remark'],
			'create_date'=>date('Y-m-d H:i:s',CORE_TIME),
			'modify_date'=>date('Y-m-d H:i:s',CORE_TIME),
			'create_user'=>$_SESSION['adminid'],
			'modify_user'=>$_SESSION['adminid']
			);

		$res2 = $this->db->model('finance_customer_sp')->add($sp_info);
		if($res && $res2){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	/**
     * 审核列表
     * @Author   yezhongbao
     * @DateTime 2016-10-24T09:31:57+0800
     * @return   [type]                   [description]
     */
    public function verifyList(){
    	$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','modify_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
    	$c_id = sget("c_id",'i'); //页码
		$where = 'c_id='.$c_id;
		$list=$this->db->model('finance_customer_sp')->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['status']=L('riskin_status')[$v['status']];
		 	$list['data'][$k]['modify_user'] = M('rbac:adm')->getUserByCol($v['modify_user']);
			$list['data'][$k]['content']=$v['content'];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
    }
    public function addButton(){
    	$type = sget('type','s');
    	if($type=='customer'){
    		$this->assign('page_title','新增客户');
			$this->display('riskcustomerin.add.html');
    	}elseif($type == 'product'){
    		$this->assign('page_title','新增商品');
			$this->display('riskproductin.add.html');
    	}else{
    		$this->assign('page_title','新增仓库');
			$this->display('riskstorein.add.html');
    	}
    }
    public function operate(){
    	$this->is_ajax=true; //指定为Ajax输出
		$data = !empty(sdata())?sdata():$_POST;
			$_data=array(
			'status'=>$data['status'],
			'modify_date'=>date('Y-m-d H:i:s',CORE_TIME),
			'modify_user'=>$_SESSION['adminid'],
		);
		if(!empty($data['pid'])){
			$res = $this->db->model('finance_product_in')->where('p_id='.$data['pid'])->update($_data);
		}elseif(!empty($data['store_id'])){
			$res = $this->db->model('finance_store_in')->where('store_id='.$data['store_id'])->update($_data);
		}
		if($res){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
    }
}