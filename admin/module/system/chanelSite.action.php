<?php
/** 
 * 渠道媒体列表
 * Andy@2013-08-28
 */
class chanelSiteAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('chanel_site');
		$this->uid = sget('uid','i');
		$this->chk_status = array(0=>'待审核',1=>'已通过',2=>'未通过');
		$this->site_type = L('site_type');
	}
	
	/**
	 * 渠道媒体列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			$where='1';
			if($this->uid>0){
				$where.=" and c.uid='$this->uid' ";	
			}
			
			$stype=sget('stype','i'); //渠道类型
			if($stype>0){
				$where.=" and c.stype='$stype' ";	
			}
			$chk_status=sget('chk_status','i'); //审核状态
			if($chk_status>0){
				$where.=" and c.chk_status=".($chk_status-1);	
			}
			
			//关键词
			$key_type=sget('key_type','s','c.sitename');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";	
			}
			
			$list=$this->db->select('c.id,c.uid,c.stype,c.sitename,c.siteurl,c.input_time,c.chk_status,c.chk_time,c.chanel_id,c.user_ip,u.username')
						->from('chanel_site c')
						->join('chanel_user u','c.uid=u.uid')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['chk_time']=$v['chk_time']>1000 ? date("Y-m-d H:i:s",$v['chk_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$this->assign('page_title','渠道媒体列表');
		$this->display('chanel.site.html');
	}

	/**
	 * 查看渠道媒体信息
	 * @access public 
	 */
	public function info(){
		$this->assign('mini_list',0);
		$this->site_type = setMiniConfig(L('site_type'));

		$id=sget('id','i');
		$info=$this->db->getPk($id);
		if($id<1 || empty($info)){
			$this->error('错误的媒体信息');
		}
		if($info['uid']>0){
			$info['user']=$this->db->model('chanel_user')->select('user_id,username,reg_time')->where('uid='.$info['uid'])->getRow();
			if($info['user']['user_id']>0){
				$info['mobile']=$this->db->model('user')->select('mobile')->where('user_id='.$info['user']['user_id'])->getOne();
			}
		}
		$info['txt_status']=$this->chk_status[$info['chk_status']];
		
		$this->assign('info',$info);
		$this->chk_status = setMiniConfig($this->chk_status);
		
		$this->assign('page_title','渠道媒体信息');
		$this->display('chanel.siteInfo.html');
	}
	
	/**
	 * 媒体信息审核
	 * @access public 
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$id=sget('id','i');
		$info=$this->db->getPk($id);
		if($id<1 || empty($info)){
			$this->error('错误的媒体信息');
		}
		if($info['chk_status']>0){
			//$this->error('媒体已经审核');
		}
		$_info=sget('info','a');
		$chanel_id=0;
		if($_info['chk_status']==1){ //通过审核
			$name=$_info['chanel'];
			if(strlen($name)<3){
				$this->error('请设置渠道号码');
			}
			$exist=$this->db->model('chanel')->where("name='$name'")->getOne();
			if($exist){
				$this->error('渠道号码已经存在');
			}
			$_data=array(
				'name'=>$name,		 
				'remark'=>$_info['sitename'],		 
				'input_time'=>CORE_TIME,		 
				'update_time'=>CORE_TIME,		 
				'status'=>1,		 
				'uid'=>$info['uid'],	
			);
			$result=$this->db->model('chanel')->add(saddslashes($_data));
			$chanel_id=$this->db->getLastID();
			if(!$result || $chanel_id<1){
				$this->error('渠道保存数据错误');	
			}			
			$this->clearMCache('chanels');
		}
		unset($_info['chanel']);
		if($chanel_id>0){
			$_info['chanel_id']=$chanel_id;
		}
		if($info['chk_time']==0){
			$_info['chk_time']=CORE_TIME;
		}
		$result=$this->db->model('chanel_site')->where('id='.$id)->update(saddslashes($_info));
		if(!$result){
			$this->error('媒体保存数据错误');	
		}	
		$this->success('审核成功');	
	}
}
?>
