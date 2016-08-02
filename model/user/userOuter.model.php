<?php
/**
* 第三方授权登录表
*/
class userOuterModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'user_outer');
	}

	//第三方授权登录绑定账号
	public function bindUser($user_id,$openid,$auth_info){
		if($this->where("outer_id='{$openid}' or user_id=$user_id")->getRow()) return false;
		$_data=array(
			'outer_id'=>$openid,
			'user_id'=>$user_id,
			'outer_source'=>$auth_info['type'],
			'outer_name'=>$auth_info['name'],
			'outer_avatar'=>$auth_info['head'],
			'input_time'=>CORE_TIME,
		);
		$this->add($_data);
		return true;
	}
}