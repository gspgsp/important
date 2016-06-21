<?php
/**
*积分明细
*/
class creditdetailAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('points_bill');
    }
    public function init(){
    	$this->display('creditdetail');
    }
    //返回积分明细
    public function get_creditdetail(){
    	//获取用户
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$result = array();
		$points = M('points:pointsBill')->getUerPoints($this->user_id);
		$result = M('touch:creditdetail')->getCreditDetail($this->user_id);
		$this->json_output(array('points'=>$points,'detail'=>$result));
	}

}