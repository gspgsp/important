<?php
/** 
 * 短信发送管理
 * Andy@2013-03-22
 */
class logsmsAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_sms');
		$this->stype=L('ui_sms_type');
		$this->status=array(1=>'待发',2=>'成功',3=>'失败');
	}

	/**
	 * 短信发送列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		$sms_channels = L('sms_channels');
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选

			$stype = sget('stype','i',0); //类型
			if($stype>0){
				$where.=" and stype='$stype' ";
			} 	
			$status = sget('status','i',0); //类型
			if($status>0){
				$where.=" and status=".($status-1);
			}

			//关键词
			$key_type=sget('key_type','s','mobile');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='msg'){
					$where.=" and msg like '%$keyword%' ";	
				}else{			
					$where.=" and $key_type='$keyword' ";
				}
			}			
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();

			foreach($list['data'] as $k=>$val){
				//银行ID
				$list['data'][$k]['bank_id']=$val['id'];
				//手机号
				$list['data'][$k]['mobile']=$val['mobile'];
				//短信内容
				$list['data'][$k]['msg']=$val['msg'];
				$list['data'][$k]['user_ip']=get_ip();
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['stype']=$this->stype[$val['stype']];
				$list['data'][$k]['status']=$this->status[$val['status']+1];
				$list['data'][$k]['chanel']=$sms_channels[$val['chanel']];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('channels',$sms_channels);
		$this->assign('page_title','短信发送列表');
		$this->display('logsms.list.html');
	}
	
	/**
	 * 历史短信记录
	 * @access public 
	 * @return html
	 */
	public function history(){
		$sms_channels = L('sms_channels');
		$action=sget('action','s');
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			
			$stype = sget('stype','i',0); //类型
			if($stype>0){
				$where.=" and stype='$stype' ";
			} 	
			$status = sget('status','i',0); //类型
			if($status>0){
				$where.=" and status=".($status-1);
			}

			//关键词
			$key_type=sget('key_type','s','mobile');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='msg'){
					$where.=" and msg like '%$keyword%' ";	
				}else{			
					$where.=" and $key_type='$keyword' ";
				}
			}			
			$list=M('public:common')->model('log_sms_history')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();

			foreach($list['data'] as $k=>$val){
				//银行ID
				$list['data'][$k]['bank_id']=$val['id'];
				//手机号
				$list['data'][$k]['mobile']=$val['mobile'];
				//短信内容
				$list['data'][$k]['msg']=$val['msg'];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['stype']=$this->stype[$val['stype']];
				$list['data'][$k]['status']=$this->status[$val['status']+1];
				//短信通道
				$list['data'][$k]['chanel']=$sms_channels[$val['chanel']];
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','历史短信记录');
		$this->display('logsms.history.html');
	}
	
	/**
	 * 重新发送短信
	 * @access public 
	 * @return array(();
	 */
	public function resend(){
		$logid = sget('logid','i',0);
		//$channel = sget('channel','i',3);

		$log = $this->db->getPk($logid) or $this->error('短信发送记录不存在');
		try {
			$sms_settings = M('system:setting')->get('sms');
			SMS::setChannel($sms_settings['main']);
			SMS::Send($log['mobile'],$log['msg']);
			$this->db->wherePk($logid)->update(array('status'=>1,'chanel'=>$channel));
			$this->success();
		} catch (Exception $e) {
			$this->error("发送失败：".$e->getMessage());
		}
	}
	
	/**
	 * 发送短信
	 * @access public 
	 * @return html
	 */
	public function send(){
		$sys = M('system:setting')->getSetting();

		if($_POST){
			$users = array();
			$sms = spost('sms','s','');
			$send_time = spost('send_time','s','');

			$m = $this->db->model('customer_contact')->select('user_id,mobile');
			if(spost('all_users','i',0)){
				$users = $m->getAll();
			}elseif($user_ids=spost('user_ids','s')){
				$users = $m->where(array('IN','user_id',explode(',',$user_ids)))->getAll();
			}
			if($channel=sget('channel','i',0)) M('system:sysSMS')->setChannel($channel);
			M('system:sysSMS')->sendBatch($users,$sms,10,strtotime($send_time)?:CORE_TIME);
			$this->success();
		}

		$this->assign('channels',L('sms_channels'));
		$this->assign('default_channel',$sys['sms_channel']);
		$this->display('logsms.send.html');
	}
}
?>
