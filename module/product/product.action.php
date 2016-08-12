<?php
/**
 * 产品信息管理
 */
class productAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->ischecked = sget('ischecked','s');
		//产品信息表
		$this->db=M('public:common')->model('product');
		//产品分类语言包
		$this->assign('product_type',L('product_type'));
		//产品状态语言包
		$this->assign('product_status',L('product_status'));
		//产品单位
		$this->assign('unit',L('unit'));
		//加工级别
		$this->assign('process_type',L('process_level'));
		//产品已审核状态
		$this->assign('pass_status',array('1'=>'上架','2'=>'下架'));
		//待审核的状态编辑
		$this->assign('edit_status',array('1'=>'上架','2'=>'下架','3'=>'待审核'));
		//产品未审核状态
		$this->assign('wait_status',array('3'=>'待审核','4'=>'审核不通过'));
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('ischecked',$this->ischecked );
		$this->assign('page_title','产品信息列表');
		$this->display('product.list.html');
	}

	/**
	 * 产品审核
	 */
	public function check(){
		$this->assign('doact','check');
		$this->assign('page_title','产品审核列表');
		$this->display('product.list.html');
	}
	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选显示类别
		$this->doact=='check' ? $where.= '  `status` in (3,4)' : $where.=' `status` in (1,2)';
		//产品分类
		$product_type=sget('product_type','s');
		if(!empty($product_type)) $where.=" and `product_type` = '$product_type' ";
		//状态
		$status =sget('status','s');
		if(!empty($status)) $where.=" and `status` = '$status' ";
		//关键词
		$key_type=sget('key_type','s','p_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='f_name'){
				$fids = implode(',',M('product:factory')->getIdsByName($keyword));
				$where.=" and f_id in ('$fids') ";
			}else{
				$where.=" and $key_type like '%$keyword%' ";
			}
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		//showTrace();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['model']=strtoupper($v['model']);
			$list['data'][$k]['product_type'] = L('product_type')[$v['product_type']]; 
			$list['data'][$k]['process_type'] = L('process_level')[$v['process_type']];
			$list['data'][$k]['f_name'] = M('product:factory')->getFnameById($v['f_id']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 产品信息
	 */
	public function info(){
		$pid=sget('pid','i',0);
		$info=$this->db->getPk($pid); //查询产品信息
		if(empty($info)){
			$this->error('错误的产品信息');	
		}
		$info['f_name']=M('product:factory')->getFnameById($info['f_id']);
		$this->assign('info',$info);
		$this->display('product.viewInfo.html');
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
		//牌号去空格转换小写
		$data['model']=strtolower(trim($data['model']));
		if($action =='edit'){
			if(M('product:product')->getPidByModel($data['model'],$data['f_id'],$id)) $this->error('相关产品已存在');
			$result = $this->db->where("id=$id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
		}else{
			if(M('product:product')->getPidByModel($data['model'],$data['f_id'])) $this->error('相关产品已存在');
			
			$result = $this->db->add($data+array('input_time'=>CORE_TIME, 'input_admin'=>$_SESSION['name'],));
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	/**
	 * 删除产品数据
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}	
		$ids=explode(',', $ids);
		foreach ($ids as $k => $v) {
			if(M('product:purchase')->getColById($v)){
				$list[$v]=M('product:purchase')->getColById($v);
				continue;
			}else{
				$result=$this->db->where("id = ($v)")->delete();
				
			}
		}
		if($list) $this->json_output(array('err'=>0,'result'=>$list));
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	/**
     * 产品信息编辑
     * @access public
    */
	public function edit(){
		$product_id=sget('id','i');
		$data = $this->db->where('id='.$product_id)->getRow();
		$data['f_name'] = M('product:factory')->getFnameById($data['f_id']);
		$this->assign('data',$data);
		$this->assign('doact','check');
		$this->display('product.edit.html');
    }
    /**
	 * 保存行内编辑工厂数据
	 * @access public
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'update_time'=>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
					'status'=>$v['status'],
				);
				$result=$this->db->wherePk($_id)->update($update);
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$changeid =  sget('changeid','i',0);
		$check_status =  sget('status','i',0);
		$status = $this->db->select('status')->wherePk($changeid)->getOne() == 1  ? 2 : 1;
		if($check_status == 3)   $status=1;
		$f_id = $this->db->select('f_id')->wherePk($changeid)->getOne();//获取厂家编号
		$this->db->startTrans();
		$res = $this->db->wherePk($changeid)->update(array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],'status'=>$status,));
		$factoryModel=M('product:factory')->where("fid={$f_id}")->update(array('status'=>$status==1?1:2,));//审核通过 厂家由锁定变为正常
		if($this->db->commit()){
		    $cache=cache::startMemcache();
		    $cache->delete('product');
		    $this->success('操作成功');
			$this->success('操作成功');
		}else{
		    $this->db->rollback();
		    $this->error('操作失败');
		}
// 		if($res){
// 			$cache=cache::startMemcache();
// 			$cache->delete('product');
// 			$this->success('操作成功');
// 		}else{
// 			$this->error('操作失败');
// 		}
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
			if(count(array_shift($sheetData)) !== 8) throw new Exception('Excel表数据格式不匹配');
			foreach($sheetData as $row){
				if(empty($row['B'])  || empty($row['C']) || empty($row['D']) || !is_numeric($row['D']) || empty($row['E']) || !is_numeric($row['E']) || empty($row['F']) || !is_numeric($row['F']) ||empty($row['G']) || !is_numeric($row['G'])) continue;//如果为空或者不是数字则不检查该行
				//检查SKU唯一性
				$order = $this->db->where("f_id='{$row['D']}' and `model`='{$row['C']}'")->getRow();
				//showTrace();
				if(!empty($order)){//本地存在该交易流水号
					$result[$row['A']] = '第【'.$row['A']."】行,该产品本地已存在";
					continue;
				}
				//检验厂家是否存在
				$existFactory = $this->db->model('factory')->getPk($row['D']);
				if(empty($order)){
					$result[$row['A']] = '第【'.$row['A']."】行,该产品对应的厂家不存在";
					continue;
				}

				//写数据到表中p2p_product
				$_infoData = array(
					'p_name'=>$row['B'],
					'model'=>$row['C'],
					'f_id'=>$row['D'],
					'product_type'=>$row['E'],
					'process_type'=>$row['F'],
					'status'=>$row['G'],
					'remark'=>$row['H'],
					'input_time'=>CORE_TIME,
					'input_admin'=>$_SESSION['name'],
				);
				//p($_infoData);
				$this->db->model('product')->add($_infoData);
			}
			
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
		$this->json_output(array('err'=>0,'result'=>$result?:false));
	}

	/**
	 * 产品更换
	 */
	public function replaceProduct(){
		$this->is_ajax=true; //指定为Ajax输出		
		$data = sdata(); //传递的参数
		$o_pid = sget('o_pid','s'); //未通过审核的产品ID
		$check_pid = $data['id'] ; //已审核的产品ID
		if(empty($o_pid) && empty($n_pid)) $this->error('错误的请求');
		$update=array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
				'status'=>4,
				);
		$result=$this->db->wherePk($o_pid)->update($update);
		$update2=array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
				'p_id'=>$check_pid,
				);
		$replace=$this->db->model('purchase')->where("`p_id` = $o_pid")->update($update2);
		if(!$result || !$replace) $this->error('操作失败');
		$this->success('操作成功');
	}
	/**
	 * 根据牌号和厂家id获取唯一的商品信息
	 */
	public function getPinfo(){
		$this->is_ajax = true;
		$model = sget('model','s');
		$fid = sget('fid','i',0);
		$data = $this->db->model('product')->where("`f_id` = '$fid' and `model` = '$model'")->getRow();
		$this->json_output($data);
	}
}