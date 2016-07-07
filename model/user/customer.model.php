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
	public function getCidByPoolCus($customer_manager){
		$result = $this->model('customer_pool')->select('c_id')->where("customer_manager='$customer_manager'")->getCol();
		return implode(',',$result);
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

}