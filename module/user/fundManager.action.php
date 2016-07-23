<?php
/**
*财务中心-资金管理
*/
class fundManagerAction extends userBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('customer');
		$cus_conta = M('user:customerContact')->getListByUserid($this->user_id);
		$this->company = $this->db->where('c_id='.$cus_conta['c_id'])->getRow();
	}
	//进入资金管理
	public function fundManager(){

		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		if($this->company['organization_state']==1 || $this->company['organization_state']==4){
			$this->display('identify');
		}elseif ($this->company['organization_state']==2) {
			// $page=sget('page','i',1);
			// $page_size=3;
			//$data = $this->_getWaterBill($page,$page_size);
			// $this->fundManager['bank_account_state'] = L('bank_account_state')[$this->fundManager['bank_account_state']];
			// $this->assign('detail',$this->fundManager);
			$data = $this->capitalDetail();
			$this->assign('detail',$data['detail']);
			$this->assign('bill',$data['bill']);
			$this->assign('pages',$data['pages']);
			$this->display('cus_reg_suc');
		}
		//$this->display('identify');
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
			$result = M('user:fundManager')->chkIsLegal($data);
			if($result['err']>0) $this->error($result['msg']);
			//$this->display('cus_reg_suc');
			$this->success($result['msg']);
		}
	}
	//资金管理详情表单数据
	public function capitalDetail(){
		if($this->company['organization_state']==3){
			//其他情况会调用调用到这里
			$page=sget('page','i',1);
			$page_size=2;
			$data = $this->_getWaterBill($page,$page_size);
			$result = $this->_getAccountDetail();
			// $this->fundManager['bank_account_state'] = L('bank_account_state')[$this->fundManager['bank_account_state']];
			// $this->json_output(array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']));
			return array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']);
			// $this->assign('detail',$this->fundManager);
			// $this->assign('bill',$data['result']);
			// $this->assign('pages',$data['pages']);
			// $this->display('capital_detail');
			//
		}elseif ($this->company['organization_state']==2) {
			//第一次认证时会调用本方法
			$result = $this->_getAccountDetail();
			// $this->json_output(array('detail'=>$result));
			return array('detail'=>$result);
		}
	}
	//获取当前用户账户详情(银行信息在usaccount表)
	private function _getAccountDetail(){
			$result = $this->company;
			foreach ($result as $key => $value) {
				if($key == 'organization_state')
				$result['organization_state'] = L('organization_state')[$value];
			}
			return $result;
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