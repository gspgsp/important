<?php
/**
*个人中心控制器
*/
class personalcenterAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('customer_contact');
    }
    public function init(){
    	$this->display('personalcenter');
    }
    public function getPersonalCenter(){
        $this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
        $thumb = M('touch:personalcenter')->getUserThumb($this->user_id);
        $name = M('touch:personalcenter')->getUserName($this->user_id);
        $quotationCount = count(M('touch:purchase')->getPurchase($this->user_id,2));
        $purchaseCount = count(M('touch:purchase')->getPurchase($this->user_id,1));
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
        if($name){
            $this->json_output(array('thumb'=>$thumb,'name'=>$name,'qcount'=>$quotationCount,'pcount'=>$purchaseCount,'points'=>$points));
        }else{
            $this->json_output(array('err'=>2,'msg'=>'没有该用户!'));
        }

    }
}