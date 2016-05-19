<?php
/**
*兑换记录(兑换订单)
*/
class creditrecordAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('points_order');
    }
    public function init(){
    	$this->display('creditrecord');
    }
    //返回积分明细
    public function get_creditRecord(){
    	//获取用户
    	if(!$_SESSION['uid'])
			{
				$this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
			}
		$uid = $_SESSION['uid'];
		$result = array();
		if($uid>0){
			$result = M('touch:creditRecord')->getCreditRecord($uid);
		}
		$this->json_output($result);
	}
}