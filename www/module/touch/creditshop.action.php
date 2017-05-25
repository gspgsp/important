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
        $gtype = sget('gtype','i');
		$result = array();
		$points = M('points:pointsBill')->getUerPoints($this->user_id);
		if(!$result = M('touch:creditshop')->getCreditShop($gtype)) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据!'));
            foreach ($result as &$v) {
                $v['thumb']=FILE_URL."/upload/".$v['thumb'];
            }
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
        $result['image']=FILE_URL."/upload/".$result['image'];
        $result['thumb']=FILE_URL."/upload/".$result['thumb'];
        $this->json_output($result);
    }
    //商品兑换获取用户数据
    public function getUserProduct(){
        $this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
        if(!$userInfo = M('user:customerContact')->getListByUserid($this->user_id)) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
        $this->json_output(array('err'=>0,'userInfo'=>$userInfo));
    }
}