<?php
/**
 *中晨报价管理
 */
class zcquoteAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		//产品信息表
		$this->db=M('public:common')->model('purchase');
		$this->assign('product_type',L('product_type'));//产品分类语言包
		$this->assign('product_status',L('product_status'));//产品状态
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('cargo_type',L('cargo_type'));//期货现货
		$this->assign('bargain',L('bargain'));//是否可以议价
		$this->assign('status',L('purchase_status'));//采购状态
		$this->assign('ischecked',sget('doact','s',''));
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
		}elseif($action=='republish'){ //从新发布
			$this->_republish();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}elseif($action=='info'){ //获取列表
			$this->_info();exit;
		}
		$this->assign('slt','slt');
		$this->assign('ctype','1');
		$this->assign('page_title','报价列表');
		$this->display('zcquote.list.html');
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
		$this->assign('page_title','报价列表');
		$this->display('zcquote.list.html');
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
		$where=" 1  and p.`type` = 2  and `is_union` = 1 ";   //type 1 采购 2报价  is_union 1为中晨的 0的话为非中晨
		$sTime = sget("sTime",'s','p.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		// 状态
		$status = sget("status",'s',''); 
		if($status!='') $where.=" and p.status='$status' ";
		//品种
		$product_type = sget('product_type','s','');
		$bargain = sget('bargain','s','');
		if($bargain!='') $where.=" and p.bargain = '$bargain' ";
		//关键词搜索
		$key_type=sget('key_type','s','p.bargain');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'f_name'){
				$result = M('product:factory')->getIdsByName($keyword);
				$result = implode($result,',');
				$where.=" and pd.f_id in ($result) ";
			}else if($key_type == 'username'){
				//方法是getOne,所以不考虑同名的情况
				$ids = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager`='$ids' ";
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
			$list['data'][$k]['bargain'] = L('bargain')[$v['bargain']];
			$list['data'][$k]['username'] = M('rbac:adm')->getUserByCol($v['customer_manager'],'name');
			if($v['origin']){
				$areaArr = explode('|', $v['origin']);
				$list['data'][$k]['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
			}
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
				'update_admin'=>$_SESSION['name'],
				'chk_time'=>CORE_TIME,
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
			if($info['origin']){   //原省市的逻辑不要了
				$areaArr = explode('|', $info['origin']);
				if($info['provinces']>0){
					$info['company_province'] = $info['provinces'];
				}else{
					$info['company_province'] = $areaArr[0];
				}
				$info['company_city']=$areaArr[1];
			}
			$info['company_province'] = $areaArr[0];
			$f_name = M('product:product')->getFnameByPid($info['p_id']); //厂家名称
			$this->assign('data',$info);
			$this->assign('c_name',$c_name);
			$this->assign('f_name',$f_name);
		}
		$this->assign('id',$id);
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
		$this->assign('page_title','手动添加采购信息');
		$this->display('zcquote.add.html');
	}
	/**
	 * 提交订单详细信息
	 */
	public function _republish(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('操作有误');	
		}
		$_data = array(
			'input_admin' => $_SESSION['name'],
			'input_time' => CORE_TIME,
		);
		foreach ($data as $k => $v) {
			$info = M('product:purchase')->getInfoById($v['id']);
			unset($info['id']);
			unset($info['input_time']);
			unset($info['input_admin']);
			unset($info['update_time']);
			unset($info['update_admin']);
			unset($info['status']);
			$info['number'] = $v['number'];
			$info['unit_price'] = $v['unit_price'];
			$this->db->add($info+$_data);
		}
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
		$data['type'] = 2;
		$data['origin']= $data['company_province'].'|'.$data['company_city'];//组合区域
		$data['provinces'] = $data['company_province'];
		if($data['company_province']>0) $data['area'] = M('system:region')->get_area($data['company_province']);//获取华东华南归属
		$model = trim($data['model']);
		//公共数据
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'is_union'=>1,
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
		//数据添加操作
		if($data['id']>0){
			if($this->db->where("id = $id")->update($data+array('update_time'=>CORE_TIME,'update_admin'=>$_SESSION['username'],)))  $this->success('操作成功');
	
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
	 * Excel导入
	 */

	public function inputExcel(){
		$this->is_ajax = true;
		$ctype = sget('ctype','i',0);
		E('PHPExcel',APP_LIB.'extend');

		if(empty($_FILES['check_file']) || $_FILES['check_file']['error']) $this->error('文件上传失败！');

		$result = array();
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['check_file']['tmp_name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
   
			if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
			if(count(array_shift($sheetData)) !== 11) throw new Exception('Excel表数据格式不匹配');

			$error=array();
 			foreach($sheetData as $row){
				//如果为空或者不是数字则 不检查导入该行
				if(empty($row['A']) || empty($row['B']) || empty($row['C']) || empty($row['D']) || empty($row['E']) || empty($row['F']) || empty($row['G']) || empty($row['H']) || empty($row['I']) || !is_numeric($row['F']) || !is_numeric($row['G']) ){
					$error['number']+=1;
					$error['err'][]='数据不规范';
					continue;
				}

				//获取公司id
				$c_id = $this->db->model('customer')->select('c_id')->where('c_name='.'"'.$row['A'].'"')->getOne();
				if (!$c_id){
					$error['number']+=1;
					$error['err'][]=$row['A'].'公司名有误';
					continue;
				}

				//获取厂家f_id
				$f_id = M('product:factory')->getIdByFName($row['D']);
				if (!$f_id) {
					$error['number']+=1;
					$error['err'][]=$row['D'].'厂家名有误';
					continue;
				}

				//获取产品类型
				$rowB = strtoupper($row['B']);
				$product_type=array_flip(L('product_type'))[$rowB];
				if(!$product_type){
					$error['number']+=1;
					$error['err'][]=$row['B'].'产品类型有误';
					continue;
				}

				//获取默认联系人user_id
				$user_id = $this->db->model('customer_contact')->select('user_id')->where('c_id='.$c_id.' and is_default=1')->getOne();
				if (!$user_id){
					$error['number']+=1;
					$error['err'][]=$row['A'].'联系人有误';
					continue;
				}

				//获取交货地的id
				$add_id = $this->db->model('lib_region')->select('id')->where('name='."'".$row['H']."'")->getOne();
				if (!$add_id){
					$error['number']+=1;
					$error['err'][]=$row['H'].'交货地有误';
					continue;
				}

				//获取产品的ID
				$p_id = M('product:product')->getPidByModel($row['C'],$f_id);
				if (!$p_id){
					$error['number']+=1;
					$error['err'][]=$row['C'].'对应商品不存在';
					continue;
				}

				//获取产品属性，如：加工级别
				// $process_type=array_flip(L('process_level'));
				// $process_type=isset($process_type[$row['E']])?$process_type[$row['E']]:11;

				$process_type = $this->db->model('product')->select('process_type')->where("id=".$p_id)->getOne();
				if(!empty($row['E'])){
					$process = L('process_level')[$process_type];
					if($row['E'] != $process){
						$error['number']+=1;
						$error['err'][]=$row['C'].'加工级别有误';
						continue;
					}
				}
				//写数据到表中p2p_product
				$_infoData = array(
					'c_id'		=>$c_id,
					'user_id'	=>$user_id,
					'product_type'=>$product_type,
					'model'		=>$row['C'],
					'f_id'		=>$f_id,
					'process_type'=>$process_type,
					'number'	=>$row['F'],
					'unit_price'=>$row['G'],
					'provinces' =>$add_id,					
					'bargain'	=>$row['I']=='是'?2:1,		//1可议价 2实价
					'store_house'=>$row['J'],
					'remark' 	=>$row['K'],
					//牌号和厂家id取得商品id
					'p_id'   	=>$p_id,
					// 'cargo_type'=>$ctype,	//1现货 2期货
					'status' 	=>2,		//审核通过
					'type'		=>2,		//1采购 2报价
					'is_union'	=>1,		//是否是中晨的报价 0否1是
					'sync'		=>1,		//0后台添加 1更新过来
					'customer_manager'=>$_SESSION['adminid'],//谁导入就是谁发的采购信息
					'input_time'=>CORE_TIME,
					'input_admin'=>$_SESSION['username'],
				);

				$this->db->model('purchase')->add($_infoData);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());			
		}
		$this->json_output(array('err'=>$error['number'],'result'=>!$error['err']?:$error['err']));
	}

	/**
	 * 更新当天数据
	 */
	public function sync(){
		set_time_limit(0);
		$result = $this->db->model('share')->where("`sync`=0")->order('input_time desc')->getAll();
		$this->db->startTrans();
		foreach ($result as $v) {
			$_data  =array();
			$_data['type']=$v['type']=='供应'?2:1;
			$id = M('product:factory')->getIdByFName($v['factory']);
			if(!$id) continue;
			$p_id = M('product:product')->getPidByModel($v['grade'],$id);
			if(!$p_id) continue;
			$_data['p_id'] = $p_id;
			$_data['number'] = $v['num'];
			$_data['unit_price'] = $v['price'];
			$_data['store_house'] = $v['store'];
			$_data['bargain']=$v['true_price']=='可议价'?1:2;
			$_data['shelve_type']=$v['status']=='上架'?1:2;
			$_data['remark'] = $v['remark'];
			$_data['input_time'] = $v['input_time'];
			$_data['input_admin'] = $v['uname'];
			$_data['customer_manager'] = $v['uid'];
			$_data['cargo_type']=$v['stock']=='现货'?1:2;
			$_data['origin']='上海';
			$_data['provinces']=25;//lib_region表中上海的id为25
			$_data['sync'] = 1;
			if($id>0){
				//`ship_type`配送','自提'配送方式, is_top是否置顶 1是 0否,content文本格式,is_stock是否库存信息 0不是 1是,cost成本价,supplier供应商,pay付款,(前台传到后台没有用到的字段)
				$sql1 = $this->db->model('purchase')->add($_data);
				if($sql1){
					$this->db->model('share')->where("id=".$v['id'])->update('`sync` = 1');
				}	
			}
		}
		if($this->db->commit()){
			$this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('数据处理失败');
		}

			
	}

	/**
	 * 导出excel
	 * @access public 
	 * @return html
	 */
	public function download(){

		$sortField = sget("sortField",'s','p.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and p.`type` = 2  and `is_union` = 1 ";   //type 1 采购 2报价  is_union 1为中晨的 0的话为非中晨
		$sTime = sget("sTime",'s','p.input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		// 状态
		$status = sget("status",'s',''); 
		if($status!='') $where.=" and p.status='$status' ";
		//品种
		$product_type = sget('product_type','s','');
		$bargain = sget('bargain','s','');
		if($bargain!='') $where.=" and p.bargain = '$bargain' ";
		//关键词搜索
		$key_type=sget('key_type','s','p.bargain');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'f_name'){
				$result = M('product:factory')->getIdsByName($keyword);
				$result = implode($result,',');
				$where.=" and pd.f_id in ($result) ";
			}else if($key_type == 'username'){
				//方法是getOne,所以不考虑同名的情况
				$ids = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager`='$ids' ";
			}else{
				$where.=" and `$key_type`='$keyword' ";
			}	
		}
		//筛选领导级别
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$where .= " and `customer_manager` in ($sons) ";
		}
		$orderby = "$sortField $sortOrder";
		$list=$this->db->select("p.*,pd.model, pd.f_id, pd.product_type, pd.process_type, pd.unit")
				->from('purchase p')->join('product pd','pd.id = p.p_id')
				->where($where)
				->order("$sortField $sortOrder")
				->getAll();
		foreach($list as &$val){
			$val['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			$val['update_time']=$val['update_time']>1000 ? date("Y-m-d H:i:s",$val['update_time']) : '-';
			$val['product_type'] = L('product_type')[$val['product_type']];
			$val['f_id'] = $this->_getFactoryName($v['f_id']);
			$val['process_type'] = L('process_level')[$val['process_type']];
			$val['bargain'] = L('bargain')[$v['bargain']];
			$val['username'] = M('rbac:adm')->getUserByCol($val['customer_manager'],'name');
			if($val['origin']){
				$areaArr = explode('|', $val['origin']);
				$val['origin'] = M('system:region')->get_name(array($areaArr[0],$areaArr[1]));
			}
			if($val['provinces']>0){
				$val['provinces'] = M('system:region')->get_name($val['provinces']);
			}
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';

		$str .= '<tr><td>品种</td><td>牌号</td><td>厂家</td><td>加工级别</td>
					<td>数量【吨】</td><td>价格【元/吨】</td><td>采购</td><td>省份</td>
					<td>是否实价</td><td>仓库</td><td>备注</td><td>创建时间</td><td>更新时间</td>
					<td>交易员</td>
				</tr>';
		foreach($list as $k=>$v){
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['product_type']."</td><td>".$v['model']."</td><td>".$v['f_id']."</td><td>".$v['process_type']."</td>
						<td>".$v['number']."</td><td>".$v['unit_price']."</td><td>".$v['supply_count']."</td><td>".$v['provinces']."</td>
						<td>".$v['bargain']."</td><td>".$v['store_house']."</td><td>".$v['remark']."</td><td>".$v['input_time']."</td><td>".$v['update_time']."</td>
						<td>".$v['username']."</td>
					</tr>";
		}
		$str .= '</table>';
		$filename = 'zcquote.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
}