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
    	if(!$_SESSION['uid'])
			{
				$this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
			}
		$uid = $_SESSION['uid'];
		$result = array();
		if($uid>0){
			$points = M('points:pointsBill')->getUerPoints($uid);
			$result = M('touch:creditdetail')->getCreditDetail($uid);
		}
		$this->json_output(array('points'=>$points,'detail'=>$result));
	}
	//签到
	
	//兑换
}