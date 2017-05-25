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
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$result = array();
		$result = M('touch:creditRecord')->getCreditRecord($this->user_id);
        foreach ($result as  &$v) {
            $v['thumb']=FILE_URL."/upload/".$v['thumb'];
        }
		$this->json_output($result);
	}
}