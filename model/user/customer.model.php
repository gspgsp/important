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
}