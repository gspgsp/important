<?php
/**
*积分商城
*/
class creditshopAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('points_bill');
    }
    public function init(){
    	$this->display('creditshop');
    }
    //返回积分明细
    public function get_creditshop(){
    	//获取用户
		$uid = $_SESSION['uid'];
		$result = array();
		if($uid>0){
			$points = M('points:pointsBill')->getUerPoints($uid);
			$result = M('touch:creditshop')->getCreditShop($uid);
		}
		$this->json_output(array('points'=>$points,'shop'=>$result));

	}
}