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
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$result = array();
		$points = M('points:pointsBill')->getUerPoints($this->user_id);
		$result = M('touch:creditshop')->getCreditShop();

		$this->json_output(array('points'=>$points,'shop'=>$result));

	}
    //商品详情页
    public function shopDetail(){
        $this->display('shopdetail');
    }
    //点击返回商品详情
    public function get_shopDetail(){
        $gid = sget('gid','i',0);
        $result = M('touch:creditshop')->getShopDetail($gid);
        $this->json_output($result);
    }
}