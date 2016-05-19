<?php
/**
*求购控制器
*/
class mypurchaseAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db = M('public:common')->model('purchase');
    }
    public function init(){
        $this->display('mypurchase');
    }
    //返回求购信息
    public function get_purchase(){
    	//获取求购信息的id
        $user_id = $_SESSION['uid'];
    	$result = array();
    	if($user_id>0){
    		$result = M('touch:purchase')->getPurchase($user_id);
    	}
    	$this->json_output($result);
    }
}