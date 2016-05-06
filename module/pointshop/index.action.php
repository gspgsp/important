<?php
/**
 * 积分商城
 */
class indexAction extends homeBaseAction{
	protected $userModel,$pointsGoodsModel,$pointsSingModel,$signDay,$today,$weekStart;
	public function __init(){
		$this->pointsGoodsModel = M('points:pointsGoods');
		$this->userModel = M('public:user');
		$this->pointsSingModel = M('points:pointsSign');
        $this->signDay = array('1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, );
        $this->today = date('w');
        // 每周开始时间
        $this->weekStart = strtotime(date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
	}
	public function init()
	{

		$p=sget('page','i',1);
		$size=10;
		$where='1';
		$where.=" and status=1";

		if($cate_id=sget('cate','i',0)){
			$where.=" and cate_id=$cate_id";
		}
		if($order=sget('order','s','sort')){
			$orderBy="$order asc";
		}
		$list=$this->pointsGoodsModel->where($where)->order($orderBy)->page($p,$size)->getPage();
		$this->pages = pages($list['count'], $p, $size);
		$this->assign('list', $list);
		$this->assign('order', $order);
		$this->assign('cate', $cate_id);
		$this->assign('signDay', $this->signDay);
		$this->assign('today', $this->today);
		$this->display('index.html');
	}


	public function checklogin()
	{
		$this->is_ajax=true;
		if($this->user_id<=0){
			json_output(array('err'=>1,'msg'=>'未登录'));
		}else{
			json_output(array('err'=>0));
		}
	}

	public function addorder()
	{
		if($_POST){
			$this->is_ajax=true;
			if($this->user_id<=0) $this->error('未登录');
			$data=saddslashes($_POST);
			foreach ($data as $key => $value) {
				if(empty($value)) $this->error('信息不完整');
			}
			if($data['code']!='123') $this->error('验证码错误');
			if(!is_mobile($data['phone'])) $this->error('错误的联系电话');
			$uinfo=M('public:common')->model('user_info')->where("user_id=$this->user_id")->getRow();
            $gid=sget('gid','i',0);
            if(!$goods=$this->pointsGoodsModel->getPk($gid)) $this->error('您兑换的商品已下架');
            if($goods['status']==2) $this->error('您兑换的商品已下架');
            if($goods['num']<=0) $this->error('您兑换的商品库存不足');
            if($uinfo['points']<$goods['points']) $this->error('您的积分不足');

            $model = M('points:pointsOrder');
            $billModel=M('points:pointsBill');
            $_orderData = array(
                'status' => 1,
                'create_time'   => CORE_TIME,
                'order_id'      => $this->buildOrderId(),
                'goods_id'      => $goods['id'],
                'receiver'      => $data['receiver'],
                'phone'         => $data['phone'],
                'address'       => $data['address'],
                'uid'           => $this->user_id,
                'usepoints'     => $goods['points'],
            );
            $model->startTrans();
            try {
            	if(!$model->add($_orderData)) throw new Exception('系统错误，无法兑换。code:101');
            	if(!$billModel->decPoints($goods['points'], $this->user_id, 5)) throw new Exception('系统错误，无法兑换。code:102');
            	
            } catch (Exception $e) {
            	$model->rollback();
            	$this->error($e->getMessage());
            }

            $model->commit();




		}
	}

	//生产订单号
	protected function buildOrderId(){
		return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}
}