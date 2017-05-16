<?php
/** 
 * 短信发送管理
 * Andy@2013-03-22
 */
class sendSmsAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_sms');
		$this->stype=L('ui_sms_type');
		$this->status=array(1=>'待发',2=>'成功',3=>'失败');
		$this->assign('offers_id',sget('offers_id','i'));
		$this->assign('customer_manager',sget('customer_manager','i'));
	}

	/**
	 * 短信发送列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		$sms_channels = L('sms_channels');
		$uid = sget('uid','i',0);
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			
			$customer_manager=sget('customer_manager','i');//报价平台传来的业务员id
			$offers_id=sget('offers_id','i');//报价平台传来的报价id
			if($offers_id)  $where.=" and FIND_IN_SET ($offers_id,offers_ids_str) ";
			
			//处理报价过来的短信查看
			if(!empty($customer_manager) && !empty($offers_id)){
				$orderby = " order by $sortField $sortOrder";
				$list['data'] = $this->db->model('log_sms')->getAll("SELECT * FROM p2p_log_sms_history AS sh WHERE 1 AND FIND_IN_SET ({$offers_id},offers_ids_str)
				UNION 
				SELECT * FROM p2p_log_sms AS s WHERE 1 AND FIND_IN_SET ({$offers_id},offers_ids_str)".$orderby.' limit '.($page)*$size.','.$size);
				$list['count'] = $this->db->model('log_sms')->getOne("SELECT SUM(count1) FROM (SELECT COUNT(id) AS count1 FROM p2p_log_sms_history AS sh WHERE 1 AND FIND_IN_SET ({$offers_id},offers_ids_str)
				UNION 
					SELECT COUNT(id) AS count1 FROM p2p_log_sms AS s WHERE 1 AND FIND_IN_SET ({$offers_id},offers_ids_str)) AS a");
			}
			foreach($list['data'] as $k=>$val){
				//手机号
				$list['data'][$k]['mobile']=$val['mobile'];
				//短信内容
				$list['data'][$k]['msg']=$val['msg'];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['stype']=$this->stype[$val['stype']];
				$list['data'][$k]['status']=$this->status[$val['status']+1];
				$list['data'][$k]['chanel']=$sms_channels[$val['chanel']];
				if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0 && $_SESSION['adminid'] != 10 && $_SESSION['adminid'] != 11 && $_SESSION['adminid'] != 991){
					$res = M('user:customerContact')->checkUserIdByCustomerManager($_SESSION['adminid'],$val['user_id']);
					if(!$res){
						$list['data'][$k]['mobile']='***';
					}
				}
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('channels',$sms_channels);
		$this->assign('uid',$uid);
		$this->assign('page_title','短信发送列表');
		$this->display('logsms.list.html');
	}
}
?>
