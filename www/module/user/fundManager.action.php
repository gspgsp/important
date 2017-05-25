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
		$this->act='fundManager';
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		if(($this->company['organization_state']==1 )|| ($this->company['organization_state']==4)){
			$this->display('identify');
		}elseif ($this->company['organization_state']==2) {
			// $page=sget('page','i',1);
			// $page_size=3;
			//$data = $this->_getWaterBill($page,$page_size);
			// $this->fundManager['bank_account_state'] = L('bank_account_state')[$this->fundManager['bank_account_state']];
			// $this->assign('detail',$this->fundManager);
			try {
			    $bind = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
			    $params = array(
			        'mallID'     => $bind->mallID,
			        'payType'    => '09011',
			        'memCode'    => $this->company['c_id'],
			        'memName'    => $this->company['c_name'],
			    );
			    $reqdata=$bind->memberbindquery(json_encode($params));
			    $rtnbind = json_decode($reqdata);
			    //101034：已绑定 101032：未绑定
			    if ($rtnbind->payStatus == "101034") {
			        $this->assign('ifbind',1);
    	    	    $params = array(
    	    	        'memCode' => $this->company['c_id'],
    	    	        'payType' => '06011',
    	    	        'mallID' => $bind->mallID,
    	    	    );
			       $reqdata2 = $bind->AccountQuery(json_encode($params));
			       $rtnamt = json_decode($reqdata2);
			       $this->assign('freeAmt',$rtnamt->freeAmt);
			       $this->assign('lockedAmt',$rtnamt->lockedAmt);
			    }else{
			        $this->assign('ifbind',0);
			        $this->assign('freeAmt',0);
			        $this->assign('lockedAmt',0);
			    }
			} catch (Exception $e) {
			    $this->assign('ifbind',0);
			    $this->assign('freeAmt',0);
			    $this->assign('lockedAmt',0);
			}
			$data=$this->_getWaterBill();
//			$data = $this->capitalDetail();
			$this->assign('data',$data);
			$this->assign('detail',$data['detail']);
			$this->assign('bill',$data['bill']);
			$this->assign('pages',$data['pages']);
			$this->assign('c_name',$this->company['c_name']);
			$this->assign('c_id',$this->company['c_id']);
			$this->assign('organization_state',$this->company['organization_state']);
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
		if($this->company['organization_state']==2){
			//其他情况会调用调用到这里
//			$page=sget('page','i',1);
//			$page_size=2;
			$data = $this->_getWaterBill();
//			$result = $this->_getAccountDetail();
			// $this->fundManager['bank_account_state'] = L('bank_account_state')[$this->fundManager['bank_account_state']];
			// $this->json_output(array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']));
//			return array('detail'=>$result,'bill'=>$data['result'],'pages'=>$data['pages']);
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
	private function _getWaterBill(){
		 $result = $this->db->model('collection')->select('id,order_type,c_id,total_price,order_sn,input_time')->where('c_id='.$_SESSION['uinfo']['c_id'])->order('input_time')->limit('3')->getAll();
		  return empty($result)?array():$result;
		//获取资讯分页列表
//		$result = $this->db->model('account_detail')->where('user_id='.$this->user_id)->order('input_time desc')->page($page,$page_size)->getPage();
//		foreach ($result['data'] as $key => $value) {
//			$result['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
//			$result['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
//		}
//		$pages = pages($result['count'], $page, $page_size);
//		return array('result'=>$result['data']);
	}
	//返回上一界面
	public function getBackLast(){
		$this->display('identify');
	}
	
	/**
	 * 下载附件
	 * @access private
	 */
	public function downloadAdjunct(){
	    $this->is_ajax=true; //指定为Ajax输出
// 	    $data = sdata(); //获取UI传递的参数
// 	    if(empty($data)) $this->error('错误的操作');
	
	    header("Content-type:text/html;charset=utf-8");
	    //用以解决中文不能显示出来的问题
	    $file_name=iconv("utf-8","gb2312",'企业操作员授权委托书.doc');//16/06/16/57629c0249c65.doc
	    $file_sub_path=ROOT_PATH.'../static/upload/'; //static.svnonline.com/upload/
	
	    $file_path=$file_sub_path.$file_name;
	    //首先要判断给定的文件存在与否
	    if(!file_exists($file_path)){
	        echo "没有该文件";
	        return ;
	    }
	    $fp=fopen($file_path,"r");
	    $file_size=filesize($file_path);//22
	
	    //下载文件需要用到的头
	    Header("Content-type: application/octet-stream");
	    Header("Accept-Ranges: bytes");
	    Header("Accept-Length:".$file_size);
	    Header("Content-Disposition: attachment; filename=".$file_name);
	    $buffer=1024;
	    $file_count=0;
	    //向浏览器返回数据
	    while(!feof($fp) && $file_count<$file_size){
	        $file_con=fread($fp,$buffer);
	        $file_count+=$buffer;
	        echo $file_con;
	    }
	    fclose($fp);
	
	}
	
}