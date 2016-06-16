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
		$user_name = $this->model('customer_contact')->select('mobile')->where('user_id='.$user_id)->getOne();
		return $user_name;
	}
	public function getUserThumb($user_id){
			$thumb = $this->model('contact_info')->select('thumb')->where('user_id='.$user_id)->getOne();
		return $thumb;
	}
}