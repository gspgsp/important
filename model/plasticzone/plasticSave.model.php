<?php
/**
*塑料圈保存个人信息-gsp
*/
class plasticSaveModel extends model
{
	public function __construct() {
        parent::__construct(C('db_default'), 'customer');
    }
    public function saveSelfInfo($userid,$type,$field){//1 地址 2 性别 3 主营牌号
    	$cus_con = M('user:customerContact')->getListByUserid($userid);
    	switch ($type) {
    		case 1:
    			$where = "c_id=".$cus_con['c_id'];
    			if($this->where($where)->update(array('address'=>$field))) return true;
    			return false;
    			// break;
    		case 2:
    			$where = "user_id=".$userid;
    			$field = intval($field);
    			if($this->model('customer_contact')->where($where)->update(array('sex'=>$field))) return true;
    			return false;
    			// break;
			case 3:
    			$where = "c_id=".$cus_con['c_id'];
    			if($this->where($where)->update(array('need_product'=>$field))) return true;
    			return false;
    			// break;
    	}
    }
}