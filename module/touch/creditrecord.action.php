<?php
/**
*兑换记录(兑换订单)
*/
class creditRecordAction extends homeBaseAction
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
		$uid=sget('uid','i',0);
		$result = array();
		if($uid>0){
			$result = M('touch:creditRecord')->getCreditRecord($uid);
		}
		$this->json_output($result);
	}
}