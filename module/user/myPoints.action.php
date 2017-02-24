<?php
/**
*财务中心-我的积分
*/
class myPointsAction extends userBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('points_bill');
    }
    //我的积分详情页
    public function init(){

    	$this->display('creditshop');
    }
    //返回我的积分以及商品推荐
    public function get_creditshop(){
    	//获取用户
    	if($this->user_id<0)  $this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
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
    //积分兑换明细详情页
    public function creditDetail(){
        $this->act='creditDetail';
        $page=sget('page','i',1);
        $size=10;
        $model=M('points:pointsOrder');
        $list=$this->db->from('points_order o')
            ->join('points_goods g','o.goods_id=g.id')
            ->where("o.uid={$this->user_id}")
            ->select("o.*,g.name")
            ->page($page,$size)
            ->getPage();
        foreach ($list['data'] as &$value) {
            $value['status']=L('points_status')[$value['status']];
        }
        $this->pages = pages($list['count'], $page, $size);
        $this->assign('list',$list['data']);
    	$this->display('creditdetail');
    }
    //返回积分明细
    public function get_creditdetail(){
    	//获取用户
    	if($this->user_id<0)  $this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
    	$thumb = M('touch:personalcenter')->getUserThumb($this->user_id);
		$points = M('points:pointsBill')->getUerPoints($this->user_id);
		$page = sget('page','i',1);
		$size = sget('size','i',8);
		$list = M('user:myPoints')->getCreditDetail($this->user_id,$page,$size);
		$pages = pages($list['count'], $page, $size);
		$this->json_output(array('thumb'=>$thumb,'points'=>$points,'detail'=>$list['data'],'pages'=>$pages));
	}
	//兑换记录详情页
	public function creditRecord(){
		$this->display('creditrecord');
	}
	//返回兑换记录
    public function get_creditRecord(){
    	//获取用户
    	if($this->user_id<0)  $this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
    	$page = sget('page','i',1);
		$size = sget('size','i',8);
		$data = M('financecenter:myPoints')->getCreditRecord($this->user_id,$page,$size);
		$this->json_output(array('pages'=>$data['pages'],'pushData'=>$data['pushData']));
	}
    //返回条件查询信息
    public function getCheckInfo(){
        $optionVal = sget('opVal','i');
        if($optionVal == 3) $this->creditDetail();
        $page = sget('page','i',1);
        $size = sget('size','i',8);
        $thumb = M('touch:personalcenter')->getUserThumb($this->user_id);
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
        $list = M('user:myPoints')->getCreditDetailByOp($this->user_id,$optionVal,$page,$size);
        $pages = pages($list['count'], $page, $size);$this->assign('thumb',$thumb);
        $this->assign('points',$points);
        $this->assign('detail',$list['data']);
        $this->assign('pages',$pages);
        $this->display('creditdetail');
//        $this->json_output(array('detail'=>$list['data'],'page'=>$pages));
    }
}