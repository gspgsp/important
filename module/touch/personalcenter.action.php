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
        $user_id = $_SESSION['uid'];
        $name = M('touch:personalcenter')->getUserName($user_id);
        $purchaseCount = count(M('touch:purchase')->getPurchase($user_id));
        if($name){
            $this->json_output(array('name'=>$name,'qcount'=>$purchaseCount,'pcount'=>$purchaseCount));
        }else{
            $this->json_output(array('err'=>1,'msg'=>'没有该用户!'));
        }

    }
}