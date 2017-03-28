<?php
/**
 * 客户信息表
 */
class customerModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer');
	}
	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
	  * @return bool（true唯一）
	 */
	public function curUnique($name='legal_idcard',$value='',$c_id=0){
		$where = "$name='$value'";
		if($c_id){
			$where .= " and c_id !='$c_id'";
		}
		$exist=$this->model('customer')->select('c_id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}

	/**
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='c_name',$condition='c_id'){
		$result =  $this->model('customer')->select("$col")->where("$condition='$value'")->getOne();
		return empty($result) ? '-' : $result;
	}
	/**
	 * 更具多个字段取出一行的值
	 */
	public function getRowByName($value=0,$col='c_name',$condition='c_id'){
		$result =  $this->model('customer')->select("$col")->where("$condition='$value'")->getRow();
		return empty($result) ? '-' : $result;
	}

	/**
	 * 根据cid获取客户信用额度
	 *
	 */
	public function getRowByCredit($c_id){
		$result=$this->model('customer')->select("credit_limit,available_credit_limit")->where("c_id=".$c_id)->getRow();
		return $result;
	}

	/**
	 * 根据联系人id 取出所有的所属公司信息
	 */
	public function getInfoByUid($uid=0){
		$info=$this->model('customer_contact')->wherePk($uid)->getRow();
		if($info){
			$result =  $this->model('customer')->select("*")->where("c_id=$info[c_id]")->getRow();
		}
		return empty($result) ? array() : $result;
	}

	/**
	 * 根据模糊客户名称 搜索出对应得字段信息
	 */
	public function getInfoByCname($c_name,$keyword,$condition='c_id'){
		$result =  $this->model('customer')->select("$condition")->where("$c_name like '%$keyword%'")->getCol();
		return empty($result) ? array() : $result;
	}
	/**
	 * 根据模糊客户名称 搜索出对应得字段信息
	 */
	public function getLikeCidByCname($keyword,$condition='c_id'){
		$result =  $this->model('customer')->select("$condition")->where(" c_name like '%$keyword%'")->getCol();
		$data = implode(',',$result);
		return empty($data)? false : $data;
	}
	/**
	 * 模糊查询牌号匹配的明细
	 */
	public function getcidByCname($value=''){
		$arr=$this->select('c_id')->where("c_name like '%".$value."%'")->getAll();
		foreach ($arr as $key => $v) {
			$ids[]=$v['c_id'];
		}
		$data = implode(',',$ids);
		return empty($data)? false : $data;
	}

	/**
	 * 根据联系人id 取出该联系人和对应公司的信息
	 */
	public function getAinfoByUid($uid=0){
		if($uid>0){
			$result = $this->select('cc.*,c.c_name')->from('customer_contact  cc')->leftjoin('customer c','cc.c_id=c.c_id')->where("cc.c_id = $uid")->getRow();
		}
		return empty($result) ? array() : $result;
	}

	/**
	 * 根据客户id获取客户的所有信息
	 * @Author   cuiyinming
	 * @DateTime 2016-05-20T11:06:38+0800
	 * @return   [type]                   [description]
		*/
	public function getCinfoById($cid = 0){
		if($cid>0){
			$result = $this->model('customer')->where("c_id = $cid")->getRow();
		}
		return empty($result) ? array() : $result;
	}
	/**
	 *根据用户的c_id查出对应的公司信息（客户）
	 */
	// public function getCompanyByCid($c_id){
	// 	$result =  $this->model('customer')->where("c_id=".$c_id)->getRow();
	// 	return empty($result) ? array() : $result;
	// }
	/**
	 *根据用户的customer_name2查出对应的公司信息（客户）
	 */
	public function getCompanyByName($name){
		$result = $this->model('customer')->where("c_name='$name'")->getRow();
		return empty($result) ? array() : $result;
	}

	/**
	 * 根据customer_manager 获取所有的扩展cid(客户id)
	 */
	public function getCidByPoolCus($customer_manager=0){
		$where = " 1 ";
		if($customer_manager>0){
			$where .= " and `customer_manager` = $customer_manager ";
		}
		$result = $this->model('customer_pool')->select('c_id')->where($where)->getCol();
		return implode(',',array_unique($result));
	}
	/**传入领导上下级字符串**/
	public function getCidPoolCus($customer_manager=''){
		$where = " 1 ";
		if($customer_manager){
			$where .= " and `customer_manager` in ($customer_manager) ";
		}
		$result = $this->model('customer_pool')->select('c_id')->where($where)->getCol();
		return implode(',',array_unique($result));
	}
	/**
	 *营业执照号验证
	 */
	// public function chkIsLegal($data){
	// 	//验证姓名
	// 	if(!is_chinese($data['legal_person'])){
	// 		//排除一些特殊字符：校验中文
	// 		if(!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,25}$/', str_replace('·','',$data['legal_person'])))
	// 			return array('err'=>1,'msg'=>'请输入正确的中文姓名');
	// 	}
	// 	if(strlen($data['legal_person'])<2 || strlen($data['legal_person'])>30)return array('err'=>1,'msg'=>'请输入正确的中文姓名');

	// 	//验证身份证合法性
	// 	if(!$this->is_idcard($data['legal_idcard']))return array('err'=>1,'msg'=>'身份证号不合法');

	// 	//验证营业执照号合法性
	// 	if(!preg_match('/^(\s)*(\d{15})(\s)*$/', $data['business_licence'])) return array('err'=>1,'msg'=>'营业执照号不合法');

	// 	//写入数据库
	// 	$data['status'] = 6;
	// 	$data['bank_account_state'] = 3;
	// 	if(!M('user:customer')->where('c_id='.$_SESSION['uinfo']['c_id'])->update($data)) return array('err'=>1,'msg'=>'更新失败');
	// 	return array('err'=>0,'msg'=>'验证通过,更新成功');
	// }
	// /**
	//  * 判断身份证号格式是否正确
	//  * @return bool
	//  */
	// private function is_idcard($idcard) {
	// 	return  preg_match('/^(\s)*(\d{15}|\d{18}|\d{17}x)(\s)*$/i', $idcard);
	// }
	/**
	 * 获取当前业务员今日开发的客户
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T11:47:52+0800
	 * @param    [type]                   $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getTodayNewClientsByCustomerManager($customer_manager){
		$today_begin = strtotime(date('Y-m-d',time()));
		$now = time();
		$where = 'input_time > '.$today_begin.' and input_time < '.$now.' and customer_manager = '.$customer_manager;
		$counts = $this->select('c_id')->where($where)->getCol();
		if($counts){
			$res['num'] = count($counts);
			$res['c_id'] = '('.implode(',', $counts).')';
		}else{
			$res['num'] = 0;
			$res['c_id'] = "('')";
		}
		return $res;
	}
	/**
	 * 获取当前业务员今日成交的客户
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T11:48:49+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getTodayNewClientsOrderNums($customer_manager = 0){
		$today_begin = strtotime(date('Y-m-d',time()));
		$now = time();
		$where = 'order_type=1 and collection_status = 3 and payd_time >'.$today_begin.' and payd_time < '.$now.' and customer_manager = '.$customer_manager;
		$countsArr = $this->model('order')
							->select('distinct(c_id)')
							->where($where)
							->getCol();
		if($countsArr){
			$res['num']=count($countsArr);
			$res['c_id']='('.implode(',', $countsArr).')';
		}else{
			$res['num']=0;
			$res['c_id']="('')";
		}
		return $res;
	}
	/**
	 * 每日跟进客户数
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T14:50:24+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getTodayFollowCustomers($customer_manager = 0){
		$today_begin = strtotime(date('Y-m-d',time()));
		$countsArr = $this->model('customer_follow')
					   ->select('id')
					   ->where('customer_manager = '.$customer_manager.' and input_time >'.$today_begin)
					   ->getCol();
		if($countsArr){
			$res['num']=count($countsArr);
			$res['id']='('.implode(',', $countsArr).')';
		}else{
			$res['num']=0;
			$res['id']="('')";
		}
		return $res;
	}


	/**
	 * @param $o_id      销售订单id
	 * @param $status    状态（销售物流审:2,销售财务审(多笔):1,销售红充审:3）
	 * @param $pay_time  完成时间(先销售审核 在物流审核)
	 * @param $money     (财务申请金额)
	 */
	public function updateCreditLimit($o_id,$status,$wps,$money=''){
		if ($wps == '-') {// 减可用额度
			// 客户c_id , total_price 订单金额
			$var = $this->model('order')->select('c_id,total_price')->where('o_id='.$o_id)->getRow();
			$info = $this->model('customer')->select('credit_limit,available_credit_limit')->where('c_id=' . $var['c_id'])->getRow();
			$arr = array();
			// 销售物流审
			if ($status==2) {
				$arr['available_credit_limit'] = ($info['credit_limit'] - $var['total_price']);
			}
			//销售红充审
			if($status==3){
				$arr['available_credit_limit'] = ($info['available_credit_limit'] - $money);
			}
			$res = $this->model('customer')->where('c_id='.$var['c_id'])->update($arr);
			return $res;
		}

		if($wps=='+'){// 多笔付款 还回
			$arrs=$this->model('collection')->select('c_id')->where('o_id='.$o_id)->getRow();
			$res=$this->model('customer')->select('credit_limit,available_credit_limit')->where('c_id=' . $arrs['c_id'])->getRow();
			if($status==1){
				$arr['available_credit_limit'] = ($res['available_credit_limit'] + $money);
				$res = $this->model('customer')->where('c_id='.$arrs['c_id'])->update($arr);
				return $res;
			}
		}
	}
	/**
	 * 判断当前客户是不是被共享的
	 * @Author   cuiyinming
	 * @DateTime 2017-03-17T20:02:40+0800
	 * @param    integer                  $cid [description]
	 * @return   [type]                        [description]
	 */
	public function judgeShare($cid=''){
		$uid = $_SESSION['adminid'];
		//根据当前用户查询的这个人是不是当前的这个人下属的客户
		$sons = M('rbac:rbac')->getSons($uid);  //领导
		$exit = $this->model('customer_pool')->where("`customer_manager` in ($sons) AND `c_id` in ($cid)")->getRow();
		return empty($exit) ? false : true;
	}


}