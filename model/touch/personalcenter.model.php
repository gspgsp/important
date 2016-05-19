<?php
/**
*个人中心模型
*/
class personalcenterModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'customer_contact');
	}
	public function getUserName($user_id){
		$_key='user_name'.$user_id;
		$cache=cache::startMemcache();
		//s$cache->delete($_key);
		$data = $cache->get($_key);
		if(empty($data)){
			$user_name = $this->model('customer_contact')->select('mobile')->where('user_id='.$user_id)->getOne();
			$cache->set($_key,$user_name,86400);//加入缓存
		}
		$data = $cache->get($_key);
		return $data;
	}
}