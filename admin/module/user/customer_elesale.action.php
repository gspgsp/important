<?php 
/**
 * 淄博电销客户
 */
class customer_elesaleAction extends adminBaseAction{
	/**
	 * 初始化
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common');
	}

	/**
	 * 客户列表
	 */
	public function init(){
		$action=sget('action','s');
		//准备where搜索条件
			$where=' 1 ';
		//搜索时间类型和时间筛选
			$sTime = sget("sTime",'s','input_time'); 
			$time_condition=getTimeFilter($sTime); 
			$where.=$time_condition; 
		//判断状态是否可用
			if($status=sget('status','i')){
				if($status>0){
					$where.=' and member_status=0 and track ='.$status;
				}
			}
		//根据标题进行模糊判断
			$key_type=sget('key_type','s','name');
			$keyWords=sget('keyword','s');
			if($key_type && $keyWords){
				if($key_type=='name'){
					$where.=' and name like "%'.$keyWords.'%"';
				}elseif($key_type=='c_name'){
					$where.=' and c_name like "%'.$keyWords.'%"';
				}elseif ($key_type=='mobile') {
					$where.=' and mobile = '.$keyWords;
				}
			}
		if ($action=='grid') {
			//准备分页参数
			$pageIndex=sget('pageIndex','i',0);
			$pageSize=sget('pageSize','i',20);
			$sortField=sget('sortField','s','input_time');
			$sortOrder=sget('sortOrder','s','desc');
			//查询数据
			$list=$this->db->model('customer_tel_sale')
					->where($where)
					->page($pageIndex+1,$pageSize)
					->order("$sortField $sortOrder")
					->getPage();
			foreach ($list['data'] as $k => $v) {
				$list['data'][$k]['sex']=$v['sex']==1?'男':'女';
				$list['data'][$k]['input_time']=date('Y-m-d H:i:s',$v['input_time']);
				$list['data'][$k]['update_time']=$v['update_time']>1000?date('Y-m-d H:i:s',$v['update_time']):'--';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$this->display('elesale_customer.list.html');
	}

	/**
	 * 增加/编辑客户信息
	 */
	public function info(){
		$this->is_ajax=true;
		$edit_id=sget('edit_id','i');
		if($edit_id>0){
			$this->info=$this->db->model('customer_tel_sale')->wherePk($edit_id)->getRow();
		}
		$action=sget('action','s');
		if($action=='grid'){
			$data=sdata();
			$judge=$data['judge'];
			unset($data['judge']);
			$data['update_time']=CORE_TIME;
			if (empty($judge)) {
				$data['input_time']=CORE_TIME;
				$result=$this->db->model('customer_tel_sale')->add($data);
			}else{
				$result=$this->db->model('customer_tel_sale')->wherePk($judge)->update($data);
			}
			if ($result) {
				$this->success('操作成功！');
			}else{
				$this->error('操作失败！');
			}
		}
		$this->display('elesale_customer.add.html');
	}
	/**
	 * 删除信息
	 */
	public function del(){
		$this->is_ajax=true;
		$ids=sget('ids','s');
		if($ids==''){
			$this->error('操作有误');
		}
		$result=$this->db->model('customer_tel_sale')->where('id in ('.$ids.')')->delete();
		if($result){
			$this->success('操作成功！');
		}else{
			$this->error('数据处理失败！');
		}
	}
	/**
	 * 添加跟踪记录
	 */
	public function addRecord(){
		$this->is_ajax=true;
		$data=sdata();
		$data['track_man']=$this->db->model('customer_tel_sale')->wherePk($data['c_id'])->select('sale_man')->getOne();
		$data['track_time']=CORE_TIME;
		$result=$this->db->model('customer_track')->add($data);
		if ($result) {
			$this->success('添加记录成功！');
		}else{
			$this->error('记录添加失败！');
		}
	}
	/**
	 * 查看跟踪记录
	 */
	public function viewRecord(){
		$cid=sget('c_id','i');
		$data=$this->db->model('customer_track')->where('c_id='.$cid)->getAll();
		$this->assign('data',$data);
		$this->display('elesale_customer.view.html');
	}
	/**
	 * 放弃跟踪客户
	 */
	public function noTrack(){
		$this->is_ajax=true;
		$cid=sget('id','i');
		if($cid>0){
			$result=$this->db->model('customer_tel_sale')->wherePk($cid)->update(array('track'=>2,'sale_man'=>''));
			if ($result) {
				$this->success('放弃跟踪成功！');
			}else{
				$this->error('放弃跟踪失败！');
			}
		}
	}
	/**
	 * 添加客户跟踪人
	 */
	public function addTrack(){
		$this->is_ajax=true;
		$data=sdata();
		if (!empty($data)) {
			$result=$this->db->model('customer_tel_sale')->wherePk($data['cus_id'])->update(array('track'=>1,'sale_man'=>$data['sale_man'],'update_time'=>CORE_TIME));
			if ($result) {
				$this->success('放弃跟踪成功！');
			}else{
				$this->error('放弃跟踪失败！');
			}
		}
	}
	

} 

 ?>