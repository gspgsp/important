<?php
/**
*财务中心-资金管理
*/
class fundManagerAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('customer');
		$this->customer = M('user:customer')->getInfoByUid($this->user_id);
	}
	//进入资金管理
	public function fundManager(){

		$this->is_ajax = true;
		if($this->user_id<0) $this->error('账户错误');
		if($this->customer['status']!=6){//最好!=6/==5
			$this->display('identify');
		}elseif ($this->customer['status']==6) {
			// $page=sget('page','i',1);
			// $page_size=3;
			//$data = $this->_getWaterBill($page,$page_size);
			// $this->customer['bank_account_state'] = L('bank_account_state')[$this->customer['bank_account_state']];
			// $this->assign('detail',$this->customer);
			$data = $this->capitalDetail();
			$this->assign('detail',$data['detail']);
			$this->assign('bill',$data['bill']);
			$this->assign('pages',$data['pages']);
			$this->display('cus_reg_suc');
		}
	}
	//认证页面
	public function cusRegister(){
		$this->display('cus_register');
	}
	//资质认证填写(认证通过后跳转到详情页)
	public function certificate(){
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);//包括:legal_person legal_idcard business_licence tax_registration grounp_no legal_idcard_pic business_licence_pic tax_registration_pic organization_pic以及隐藏域的c_id值
			$result = M('user:customer')->chkIsLegal($data);
			if($result['err']>0) $this->error($result['msg']);
			//$this->display('cus_reg_suc');
			$this->success($result['msg']);
		}
	}
	//资金管理详情表单数据
	public function capitalDetail(){
		if($this->customer['bank_account_state']==2 || $this->customer['bank_account_state']==1){
			//其他情况会调用调用到这里
			$page=sget('page','i',1);
			$page_size=2;
			$data = $this->_getWaterBill($page,$page_size);
			$result = $this->customer;
			foreach ($result as $key => $value) {
				if($key == 'bank_account_state')
				$result['bank_account_state'] = L('bank_account_state')[$value];
			}
			// $this->customer['bank_account_state'] = L('bank_account_state')[$this->customer['bank_account_state']];
			// $this->json_output(array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']));
			return array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']);
			// $this->assign('detail',$this->customer);
			// $this->assign('bill',$data['result']);
			// $this->assign('pages',$data['pages']);
			// $this->display('capital_detail');
			//
		}elseif ($this->customer['bank_account_state']==3) {
			//第一次认证时会调用本方法
			$result = $this->customer;
			foreach ($result as $key => $value) {
				if($key == 'bank_account_state')
				$result['bank_account_state'] = L('bank_account_state')[$value];
			}
			// $this->json_output(array('detail'=>$result));
			return array('detail'=>$result);
		}
	}
	//获取当前用户的收支流水订单
	private function _getWaterBill($page,$page_size){
		// $result = $this->db->model('account_detail')->where('user_id='.$this->user_id)->getAll();
		//  return empty($result)?array():$result;
		//获取资讯分页列表
		$result = $this->db->model('account_detail')->where('user_id='.$this->user_id)->order('input_time desc')->page($page,$page_size)->getPage();
		foreach ($result['data'] as $key => $value) {
			$result['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
			$result['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
		}
		$pages = pages($result['count'], $page, $page_size);
		return array('result'=>$result['data'],'pages'=>$pages);
	}
	//返回上一界面
	public function getBackLast(){
		$this->display('identify');
	}
}