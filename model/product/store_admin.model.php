<?php 
//仓库锁定业务员模型
class store_adminModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'store_admin');
	}

	//根据仓库id查出此业务员id是否存在
	public function replaceByStoreId($admin_id,$store_id){
		return $this->where("`store_id` = $store_id and  `admin_id`=$admin_id")->select('id')->getAll();
	}
	/**
	 * 根据ID取指定值
	 */
	public function getColByid($sid=0){
		return $this->select("a.admin_id,a.name")->from('store_admin s')->join('admin a','a.admin_id=s.admin_id')->where('s.store_id='.$sid)->getAll();

	}
}