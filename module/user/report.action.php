<?php
/**
 * 产品信息管理
 */
class reportAction extends adminBaseAction {
	protected $db;
	public function __init(){
		$this->db=M('public:common')->model('report_user');//客户跟进信息表
		$this->assign('depart',C('depart'));//所属部门
		$this->assign('team',L('team')); //战队名称
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
		$this->assign('page_title','绩效考核列表');
		$this->display('report.list.html');
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
		$where=" 1 ";
		// 特殊时间搜索
		$status = sget("status",'s','');
		if($status=='this_week') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))) );
		}
		if($status=='last_week') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))) );
		}
		if($status=='this_month') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))) );
		}
		if($status=='last_month') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y"))) );
		}
		if (!empty($start)) {
			$where.=" and input_time >=".$start.' and input_time <='.$end;
		}else{
			//自定义时间搜索
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.= getTimeFilter($sTime); //时间筛选
		}
		//战队筛选
		$team_id=sget('team_id','i');
		if($team_id)  $where.=" and `team_id` = '$team_id' ";
		//关键词
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$ids = M('rbac:adm')->getIdByName("$keyword");
			$str=implode(',',$ids);
			$where.=" and `admin_id` in ($str)";
		}
		//筛选过滤自己的信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			$where .= " and `admin_id` in ($sons) ";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as &$v){
			$v['report_date']=$v['report_date']>1000 ? date("Y-m",$v['report_date']) : '-';
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$v['collection_time']=$v['collection_time']>1000 ? date("Y-m-d H:i:s",$v['collection_time']) : '-';
			$v['name'] = M('rbac:adm')->getUserByCol($v['admin_id']);
			// $v['depart']=C('depart')[$message['depart']];
			// $v['name']  =$message['name'];
			$depart =  M('rbac:adm')->getPartByID($v['admin_id']);
			$v['depart_name']=$depart['name'];
		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->model('report_user')->select("sum(sales) as sales, sum(buys) as buys,sum(sale_num) as sale_num, sum(buy_num) as buy_num,sum(profit) as profit")->where($where)->getRow();
			// showtrace();
			$msg="[筛选结果] 月销售吨数目标:【".$sum['sale_num']."】月采购吨数目标:【".$sum['buy_num']."】毛利目标:【".price_format($sum['profit'])."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}

	/**
	 * 弹出添加绩效考核列表
	 * @access private
	 * @return html
	 */
	public function addreport(){
		$this->display('report.add.html');
	}

	/**
	 * 添加绩效信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$data = sdata(); //传递的参数
		$team = M('rbac:adm')->getPartByID($data['admin_id']);//根据管理员id获取管理员战队id
		if(empty($data)) $this->error('错误的请求');
    		$report_date = strtotime(date('Y-m',strtotime($data['report_date'])));
    		$user_data = $this->db->select('id')
    		                  ->where("admin_id=".$data['admin_id']." and report_date = '".$report_date."'")
    		                  ->getOne();
    		if($user_data){
    			$this->error('该月指标您已导入，不可重复导入');
    		}
    		$data['team_id']=$team['id'];
    		$data['input_time']=CORE_TIME;
    		$data['input_admin']=$_SESSION['name'];
    		$data['report_date']= $report_date;
		$result = $this->db->add($data);
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	

	/**
	 * 保存行内编辑仓库数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');

		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'collection_time'  =>strtotime($v['collection_time']),
					'report_date'  =>strtotime($v['report_date']),
					'input_time'  =>strtotime($v['input_time']),
					'update_time'  =>CORE_TIME,
					'update_admin' =>$_SESSION['name'],
				)+$v;
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('factory');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
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
 			foreach($sheetData as $key=>$row){ 
 			//如果为空或者不是数字则 不检查导入该行
				if(empty($row['A'])){
					$error['number']+=1;
					$error['err'][]='第'.($key+1).'行数据不规范,存在空值或空行，请核实后再单独添加该交易员信息';
					continue;
				}		
 				$admin_id = M('rbac:adm')->getAdmin_Id($row['A']);//根据管理员name获取管理员id
 				
 				if (!$admin_id) {
 					$error['number'] += 1;
 					$error['err'][] = "【".$row['A']."】---该姓名系统不存在，请核实后再单独添加该交易员信息";
 					continue;
 				}else{
 					//处理重复导入
 					$where = 'report_date = '.strtotime(date('Y-m',CORE_TIME)).' and admin_id = '.$admin_id;
 					$result = $this->db->select('admin_id')->where($where)->getOne();
 					if($result){
 						continue;
 					}
 				}
				$team = M('rbac:adm')->getPartByID($admin_id);//根据管理员id获取管理员战队id
				//写数据到表中p2p_report_user中
				$_infoData = array(
					'admin_id'	 =>$admin_id,
					'team_goal'   =>$row['B'],
					'sale_num'   =>$row['C'],
					'profit'	 =>$row['D'],
					'buy_num'    =>$row['E'],
					'new_user'	 =>$row['F'],
					'old_user'   =>$row['G'],
					'day_call'   =>$row['H'],					
					'month_call' =>$row['I'],
					'income' 	 =>$row['J'],
					'team_id' 	 =>$team['id'],
					'input_time' =>CORE_TIME,
					'input_admin' 	 =>$_SESSION['name'],
					'report_date' 	 =>strtotime(date('Y-m',CORE_TIME)),
				);
				$this->db->add($_infoData);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());			
		}
		$this->json_output(array('err'=>$error['number']?$error['number']:0,'result'=>!$error['err']?'导入成功':$error['err']));
	}
}