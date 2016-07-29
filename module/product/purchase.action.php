<?php
/**
 * 产品报价与采购管理
 */
class purchaseAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('doact','s');
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
		$this->assign('doact',$this->doact);
		$this->assign('slt','slt');
		$this->assign('ctype','1');
		$this->assign('page_title','采购报价列表');
		$this->display('purchase.list.html');
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
		$this->display('purchase.list.html');
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
		$sortField = sget("sortField",'s','p.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and p.`type` = 1 and p.`is_union` = 0 ";   //1 采购
		if($this->doact != 'search'){
			if($slt){
				$where .=" and p.`cargo_type` = 2 "; //期货采购
			}else{
				$where .=" and p.`cargo_type` = 1 "; //现货采购
			}
		}
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
			// if($v['origin']){
			// 	$areaArr = explode('|', $v['origin']);
			// 	$list['data'][$k]['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
			// }
			if($v['provinces']>0){
				$list['data'][$k]['provinces'] = M('system:region')->get_name($v['provinces']);
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
			$p_info  = $this->db->model('product')->wherePk($info['p_id'])->getRow();
			$info = array_merge($p_info,$info);
			if($info['origin']){
				$areaArr = explode('|', $info['origin']);
				if($info['provinces']>0){
					$info['company_province'] = $info['provinces'];
				}else{
					$info['company_province'] = $areaArr[0];
				}
				$info['company_city']=$areaArr[1];
			}
			$contact=M('user:customerContact')->getListByCid($info['c_id']);
			$c_name = M('user:customer')->getColByName($info['c_id']); //客户名称
			$f_name = M('product:product')->getFnameByPid($info['p_id']); //厂家名称
			$this->assign('contact',arrayKeyValues($contact, 'user_id', 'name'));
			$this->assign('data',$info);
			$this->assign('c_name',$c_name);
			$this->assign('f_name',$f_name);
		}
		$this->assign('id',$id);

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
		$_info['provinces'] = $_info['company_province'];
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
		$id =  $data['id'];
		$utype = $data['ctype'];
		$data['origin']= $data['company_province'].'|'.$data['company_city'];//组合区域
		$data['provinces'] =  $data['company_province'];
		if($data['company_province']>0) $data['area'] = M('system:region')->get_area($data['company_province']);//获取华东华南归属
		$model = trim($data['model']);
		//公共数据
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		if($id <= 0){
			if($data['f_id']>0  && (!empty($model))){
				$p_id = M('product:product')->getPidByModel($data['model'],$data['f_id']);
				$data['p_id']  = $p_id>0 ? $p_id : M('product:product')->insertProduct(array('status'=>3,)+$data+$_data);
				$data['customer_manager'] = $_SESSION['adminid'];
			} else{
				$this->error('牌号或者厂家不能为空');
			}
		}
		//货物类型
		if($utype==1) $data['cargo_type'] = 2;
		//数据添加操作
		if($data['id']>0){
			if($this->db->where("id = $id")->update($data+array('update_time'=>CORE_TIME,'update_admin'=>$_SESSION['name'],)))  $this->success('操作成功');
		
		}else{
			if($this->db->add($data+$_data)) $this->success('操作成功');	
		}
		$this->error('添加失败');
		
	}
	/**
	 * 根据厂家id获取厂家名称
	 */
	private function _getFactoryName($id){
		$result  = $this->db->model('factory')->select('f_name')->where("`fid` = $id")->getOne();
		return empty($result) ? '-' : $result;
	}
	/**
	 * 弹出审核页面
	 * @Author   cuiyinming
	 * @DateTime 2016-07-01T15:31:38+0800
	 * @return   [type]                   [description]
	 */
	public function chkpage(){
		$id = sget('id','i',0);
		if($id<1) $this->error('信息错误');
		$this->assign('id',$id);
		$this->display('purchase.chk.html');
	}
	//审核结果提交
	public function chkSubmit(){
		$id =sget('id','i',0);
		$status = sget('status','i');
		if($id<1) $this->error('用户信息错误');
		$result = $this->db->wherePk($id)->update(array('status'=>$status,'update_time'=>CORE_TIME,'update_admin'=>$_SESSION['name']));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

	/**
	 * Excel导入
	 */

	public function inputExcel(){
		$this->is_ajax = true;
		E('PHPExcel',APP_LIB.'extend');

		if(empty($_FILES['check_file']) || $_FILES['check_file']['error']) $this->error('文件上传失败！');

		$result = array();
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['check_file']['tmp_name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
   
			if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
			if(count(array_shift($sheetData)) !== 10) throw new Exception('Excel表数据格式不匹配');

			$error=array();
 			foreach($sheetData as $row){ 				
				//如果为空或者不是数字则 不检查导入该行
				if(empty($row['A']) || empty($row['B']) || empty($row['C']) || empty($row['D']) || empty($row['E']) || empty($row['F']) || empty($row['G']) || empty($row['H']) || !is_numeric($row['A']) || !is_numeric($row['F']) || !is_numeric($row['G']) ) continue;

				//获取厂家f_id
				$f_id = M('product:factory')->getIdByFName($row['D']);
				if (!$f_id) {
					$error['number']+=1;
					$error['err'][]=$row['D'];
					continue;
				}

				//获取产品类型
				$rowB = strtoupper($row['B']);
				$product_type=array_flip(L('product_type'))[$rowB];
				if(!$product_type){
					$error['number']+=1;
					$error['err'][]=$row['B'];
					continue;
				}

				//获取加工级别
				$process_type=array_flip(L('process_level'));
				$process_type=isset($process_type[$row['E']])?$process_type[$row['E']]:11;
 				
				//获取期货周期
				$period=array_flip(L('period'));
				if(!$period=$period[$row['I']]){
					$error['number']+=1;
					$error['err'][]=$row['I'];
					continue;
				}

				//获取默认联系人user_id
				$user_id = $this->db->model('customer_contact')->select('user_id')->where('c_id='.$row['A'].' and is_default=1')->getOne();
				if (!$user_id){
					$error['number']+=1;
					$error['err'][]=$row['A'];
					continue;
				}

				//获取交货地的id
				$add_id = $this->db->model('lib_region')->select('id')->where('name='."'".$row['H']."'")->getOne();
				if (!$add_id){
					$error['number']+=1;
					$error['err'][]=$row['H'];
					continue;
				}

				//写数据到表中p2p_product
				$_infoData = array(
					'c_id'		=>$row['A'],
					'user_id'	=>$user_id,
					'product_type'=>$product_type,
					'model'		=>$row['C'],
					'f_id'		=>$f_id,
					'process_type'=>$process_type,
					'number'	=>$row['F'],
					'unit_price'=>$row['G'],
					'provinces' =>$add_id,					
					'period' 	=>$period,					
					'remark' 	=>$row['J'],
					//牌号和厂家id取得商品id
					'p_id'   	=>M('product:product')->getPidByModel($row['C'],$f_id),
					'cargo_type'=>1,	//现货
					'status' 	=>2,	//审核通过
					'type'		=>1,	//1采购 2报价
					'is_union'	=>0,	//是否是中晨的报价 0否1是
					'sync'		=>1,	//0后台添加 1更新过来
					'customer_manager'=>$_SESSION['adminid'],//谁导入就是谁发的采购信息
					'input_time'=>CORE_TIME,
					'input_admin'=>$_SESSION['name'],
				);
				$_infoData['p_id']  = $_infoData['p_id']>0 ?$_infoData['p_id'] : M('product:product')->insertProduct(array('status'=>3,)+$_infoData);

				$this->db->model('purchase')->add($_infoData);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
			
		}
		$this->json_output(array('err'=>$error['number'],'result'=>!$error['err']?:$error['err']));
	}
}