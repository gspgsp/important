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
    	$c_id = sget('c_id','i',0);
    	$result = array();
    	if($c_id>0){
    		$result = M('touch:purchase')->getPurchase($c_id);
    	}
    	$this->json_output($result);
    }
}