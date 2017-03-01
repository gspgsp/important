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
					->select("user_id,name,c_id,sex,mobile,qq,visit_count,headline_vip,cate_id,opening_date")
					->page($pageIndex+1,$pageSize)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['sex']=L('sex')[$v['sex']];
				$list['data'][$k]['c_name']= M('user:customer')->getColByName($v['c_id']);
				$list['data'][$k]['opening_date']=$v['opening_date']>1000 ? date("Y-m-d H:i:s",$v['opening_date']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$newsCate=$this->db->model('news_cate')->select('cate_id,cate_name')->where('pid=33')->getAll();
		foreach ($newsCate as $k => $v2) {
			$temp[$k]['valueField']=$v2['cate_id'];
			$temp[$k]['textField']=$v2['cate_name'];
		}
		$this->assign('cate',$temp);		
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
		$info['input_time']=CORE_TIME;		
		$cate=explode(',', $info['cate_id']);
		unset($info['cate_id']);
		foreach ($cate as $v3) {
			$row=$this->db->model('customer_headline')->where('user_id='.$info['user_id'].' and cate_id='.$v3)->select('end_time')->order('id desc')->getOne();
			if($row != 0 && $row>CORE_TIME){
				$info['start_time']=$row;
				$info['type']=3;
			}else{
				$info['start_time']=CORE_TIME;
				$info['type']=1;
			}
			$info['end_time']=$info['start_time']+($info['year_num']*31536000);
			$info['total_time']=$info['end_time'];
			$info['cate_id']=$v3;
			$result=$this->db->model('customer_headline')->add($info);
			$cache = cache::startMemcache();
			$cache->delete($info['user_id'].'_time_'.$info['cate_id']);
		}		
		if($result){
			$cate_id=$this->db->model('customer_contact')->wherePk($info['user_id'])->select('cate_id')->getOne();
			if (!empty($cate_id)) {
				foreach ($cate as $k => $v2) {
					if (strstr($cate_id,$v2)) {
						unset($cate[$k]);
					}
				}
			}
			$cate_str=implode(',', $cate);
			if (!empty($cate_id) && !empty($cate_str)) {
				$temp=',';
			}else{
				$temp='';
			}
			$result2=$this->db->model('customer_contact')->wherePk($info['user_id'])->update(array('headline_vip'=>1,'opening_date'=>CORE_TIME,'cate_id'=>$cate_id.$temp.$cate_str));		
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
		foreach ($data as $k => $v) {				
			$data[$k]['cate_name']=$this->db->model('news_cate')->wherePk($v['cate_id'])->select('cate_name')->getOne();
			$data[$k]['start_time']=$v['start_time']>1000 ? date("Y-m-d H:i:s",$v['start_time']) : '--';
			$data[$k]['end_time']=$v['end_time']>1000 ? date("Y-m-d H:i:s",$v['end_time']) : '--';
			$data[$k]['total_time']=$v['total_time']>1000 ? date("Y-m-d H:i:s",$v['total_time']) : '已到期';
		}
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
		//获取客户的名称，手机，开通的分类ID
		$c_row=$this->db->model('customer_contact')->where('user_id='.$data['user_id'])->select('name,mobile,cate_id')->getRow();
		$data['c_name']=$c_row['name'];
		$data['mobile']=$c_row['mobile'];
		$data['input_time']=CORE_TIME;		
		//type为2表示一键清空客户所有会员分类，4表示按指定分类进行删除
		$cate_arr=explode(',', $c_row['cate_id']);
		if ($data['type']==2) {
			$update_contact=array('headline_vip'=>0,'cate_id'=>'','opening_date'=>0);
		}else{
			$cate_arr2=explode(',',$data['cate_id']);
			foreach ($cate_arr2 as $k=>$v) {
				if (!in_array($v,$cate_arr)) {
					unset($cate_arr2[$k]);	
				}
			}
			foreach ($cate_arr as $k2 => $v2) {
				if (in_array($v2,$cate_arr2)) {
					unset($cate_arr[$k2]);
				}	
			}
			if (empty($cate_arr)) {
				$update_contact=array('headline_vip'=>0,'cate_id'=>'','opening_date'=>0);
			}else{
				$cate_id=implode(',', $cate_arr);
				$update_contact=array('headline_vip'=>1,'cate_id'=>$cate_id);
			}
			$cate_arr=$cate_arr2;
		}
		if (empty($cate_arr)) {
			json_output(array('err'=>2,'msg'=>'删除的信息错误！'));
		}else{
			foreach ($cate_arr as $v) {
				$data['cate_id']=$v;
				$data['total_time']=0;
				$result=$this->db->model('customer_headline')->add($data);
			}	
		}	
		//p($data);exit;
		if($result){
			$result2=$this->db->model('customer_contact')->wherePk($data['user_id'])->update($update_contact);
			if($result2){
				$this->success('删除客户信息成功！');
			}else{
				$this->error('删除客户信息失败！');	
			}	
		}else{
			$this->error('添加会员删除纪录失败！');
		}
	}
	/**
	 * 会员分类删除默认选择
	 */
	public function getCateId(){
		$this->is_ajax=true;
		$user_id=sget('user_id','i');
		if ($user_id != 0) {
			$cate_id=$this->db->model('customer_contact')->wherePk($user_id)->select('cate_id')->getOne();
			$this->json_output(array('err'=>1,'val'=>$cate_id));
		}else{
			$this->json_output(array('err'=>0,'msg'=>'无法获取该客户的信息！'));
		}	
	}
}