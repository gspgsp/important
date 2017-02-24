<?php 
/**
 * 塑料头条会员用户
 */
class headlineAction extends adminBaseAction {
	/**
	 * 初始化
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common');
	}

	/**
	 * 会员列表
	 */
	public function lst(){
		$action=sget('action','s');
		if($action=='grid'){
		//准备where搜索条件，默认必须是会员
			$where=' `headline_vip`=1 ';
		//搜索时间类型和时间筛选
			$sTime = sget("sTime",'s','opening_date'); 
			$time_condition=getTimeFilter($sTime); 
			$where.=$time_condition; 
		//关键词类型搜索
			$key_type=sget('key_type','s','name');
			$keyword=sget('keyword','s');
			//p($keyword);exit;
		//关键词查询不限制于会员，所以收到关键词就把where条件变为1
			if (empty($time_condition) && !empty($keyword)) {	
				$where=" 1 ";
			}
			if(!empty($keyword) && $key_type=='name' ){
				$where.=" and `$key_type`  like '%$keyword%' ";
			}elseif(!empty($keyword) && $key_type=='c_id'){
				$keyword=M('user:customer')->getColByName($keyword,$key_type,'c_name');
				$where.=" and `$key_type`  = '$keyword' ";
			}elseif(!empty($keyword) && $key_type=='mobile'){
				$where.=" and `$key_type`  = '$keyword' ";
			}
		
			//准备分页参数
			$pageIndex=sget('pageIndex','i',0);
			$pageSize=sget('pageSize','i',20);
			$sortField=sget('sortField','s','opening_date');
			$sortOrder=sget('sortOrder','s','desc');
			//查询数据
			$list=$this->db->model('customer_contact')
					->where($where)
					->select("user_id,name,c_id,sex,mobile,qq,visit_count,headline_vip,closing_date,opening_date,sale_name")
					->page($pageIndex+1,$pageSize)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['sex']=L('sex')[$v['sex']];
				$list['data'][$k]['c_name']= M('user:customer')->getColByName($v['c_id']);
				$list['data'][$k]['closing_date']=$v['closing_date']>1000 ? date("Y-m-d H:i:s",$v['closing_date']) : '-';
				$list['data'][$k]['opening_date']=$v['opening_date']>1000 ? date("Y-m-d H:i:s",$v['opening_date']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}		
		$this->display('headline.list.html');
	}
	/**
	 * 插入会员开通信息
	 */
	public function addMember(){
		$this->is_ajax=true;
		$info=sdata();
		if($info['user_id']==0 || $info['year_num']==0 || empty($info['sale_name'])){
			json_output(array('err'=>1,'msg'=>'填写信息出现错误！'));
		}
		//获取客户的名称，手机
		$c_row=$this->db->model('customer_contact')->where('user_id='.$info['user_id'])->select('name,mobile')->getRow();
		$info['c_name']=$c_row['name'];
		$info['mobile']=$c_row['mobile'];
		//获取该客户对应的开通记录
		$row=$this->db->model('customer_headline')->where('user_id='.$info['user_id'])->order('id desc')->getRow();
		if (empty($row) || $row['end_time']<=CORE_TIME) {
			$info['start_time']=CORE_TIME;
		}else{
			$info['start_time']=$row['end_time'];
		}
		$info['end_time']=$info['start_time']+($info['year_num']*31536000);
		$info['input_time']=CORE_TIME;
		$info['type']=1;
		$result=$this->db->model('customer_headline')->add($info);
		//showTrace();exit();
		if($result){
			$result2=$this->db->model('customer_contact')->wherePk($info['user_id'])->update(array('headline_vip'=>1,'closing_date'=>$info['end_time'],'opening_date'=>CORE_TIME,'sale_name'=>$info['sale_name']));
			if($result2){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');	
			}	
		}else{
			$this->error('操作失败');
		}
	}
	/**
	 * 会员开通记录查看
	 */
	function memberViewflow(){
		$userid=sget('userid','i');
		if($userid<1){
			$this->error('错误的用户信息');
		}
		//获取对应ID记录
		$data=$this->db->model('customer_headline')->where('user_id='.$userid)->order('id desc')->getAll();
		if(empty($data)){
			$this->error('错误的用户信息');
		}
		$this->assign('data',$data);
		$this->assign('page_title','会员开通记录');
		$this->display('headlineMember.viewFlow.html');
	}
	/**
	 * 会员删除操作
	 */
	public function delMember(){
		$this->is_ajax=true;
		$data=sdata();
		if (empty($data) || $data['user_id']==0) {
			json_output(array('err'=>1,'msg'=>'填写信息出现错误！'));
		}
		//获取客户的名称，手机
		$c_row=$this->db->model('customer_contact')->where('user_id='.$data['user_id'])->select('name,mobile,closing_date')->getRow();
		//del_type==2：一键删除; 否则，按年数删除
		if($data['del_type']==2){
			//会员的截止日期减去对应的删减时间得到删减后的时间
			$del_time=$c_row['closing_date']-($data['year_num']*31536000);
			if ($del_time<CORE_TIME) {
				json_output(array('err'=>2,'msg'=>'您删除的时间总数超过了会员剩余时间，您可以选择一键删除会员时间！'));
			}else{
				$data['end_time']=$del_time;
				$status=1;
			}
		}else{			
			$data['end_time']=CORE_TIME;
			$data['year_num']=99;
			$status=0;
		}
		unset($data['del_type']);
		$data['start_time']=$c_row['closing_date'];		
		$data['c_name']=$c_row['name'];
		$data['mobile']=$c_row['mobile'];
		$data['type']=2;
		$data['input_time']=CORE_TIME;
		$result=$this->db->model('customer_headline')->add($data);
		if($result){
			$result2=$this->db->model('customer_contact')->wherePk($data['user_id'])->update(array('headline_vip'=>$status,'closing_date'=>$data['end_time'],'opening_date'=>CORE_TIME,'sale_name'=>$data['sale_name']));
			if($result2){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');	
			}	
		}else{
			$this->error('操作失败');
		}
	}
}