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
        if($userInfo=M('user:customerContact')->getUserInfoByid($this->user_id))
        {
            $this->assign('userInfo',$userInfo);
        }
        if( $singData = $this->pointsSingModel->where("uid={$this->user_id}")->getRow() ){
            if( $singData['sing_time'] > $this->weekStart ){
                $this->signDay = json_decode($singData['sing_status'], true);
            }
        }

		$p=sget('page','i',1);
		$size=20;
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

	// 签到
	public function signon(){
		
		$this->is_ajax(true);
        if( $this->user_id>0 ){
            // 周末不支持签到
            if( $this->today == 0 || $this->today == 6 ){
                exit(json_encode(array('status'=> 0, 'msg' => '周一到周五可以签到')));
            }
            // 随机积分
            $points = 50;
            if( $data = $this->pointsSingModel->where("uid={$this->user_id}")->getRow() ){
                //最后签到时间小于今天的凌晨时间戳可以签到
                if( $data['sing_time'] < strtotime(date('Y-m-d', time())) ){
                    //最后签到时间小于本周开始时间 重置本周签到状态
                    if( $data['sing_time'] < $this->weekStart ){
                        $statusData = $this->signDay;
                    }else{
                        $statusData = json_decode($data['sing_status'], true);
                    }
                    $statusData[$this->today] = 1;
                    $data['sing_status'] = json_encode($statusData);
                    $data['sing_time'] = time();

                    // 增加积分
                    if( array_sum($statusData) ==5 ){
                        $points = intval($points)*2;
                    }
                    $billModel=M('points:pointsBill');
                    $billModel->startTrans();
                    try {
                        if( !$this->pointsSingModel->where("uid={$this->user_id}")->update($data) ) throw new Exception('系统错误。code:201');
                        if( !$billModel->addPoints($points, $this->user_id, 1) ) throw new Exception('系统错误。code:202');
                    } catch (Exception $e) {
                        $billModel->rollback();
                        exit(json_encode(array('status' => 0, 'msg' => $e->getMessage())));
                    }
                    $billModel->commit();
                    exit(json_encode( array('status' => 1, 'msg' => '签到成功', 'points' => $points)) );
                }else{
                    exit(json_encode(array('status'=> 0, 'msg' => '今天已经签到过了')));
                }
            }else{
            	
                $this->signDay[$this->today] = 1;
                $data = array('uid' => $this->user_id, 'sing_status' => json_encode($this->signDay), 'sing_time' => time());
                $billModel=M('points:pointsBill');
                $billModel->startTrans();
                try {
                    if( !$this->pointsSingModel->add($data) ) throw new Exception('系统错误。code:201');
                    if( !$billModel->addPoints($points, $this->user_id, 1) ) throw new Exception('系统错误。code:202');
                } catch (Exception $e) {
                    $billModel->rollback();
                    exit(json_encode(array('status' => 0, 'msg' => $e->getMessage())));
                }
                $billModel->commit();
                exit(json_encode(array('status' => 1, 'msg' => '签到成功', 'points' => $points)));
            }

        }else{
            exit(json_encode(array('status' => 0, 'msg' => '签到失败')));
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
            $mcode=$data['code'];

            $result=M('system:sysSMS')->chkDynamicCode($data['phone'],$mcode);
            if($result['err']>0){
                $this->error($result['msg']);
            }
			if(!is_mobile($data['phone'])) $this->error('错误的联系电话');
			$uinfo=M('public:common')->from('customer_contact c')
                ->join('contact_info i','c.user_id=i.user_id')
                ->select('c.*,i.points')
                ->where("c.user_id=$this->user_id")->getRow();
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
            $this->success('兑换成功');

		}
	}

	//生产订单号
	protected function buildOrderId(){
		return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}

        /**
         * 发送手机验证码
         * @access public
         * @return html
         */
        public function sendmsg(){
            $this->is_ajax=true;
            //验证手机，邮箱，验证码
            $mobile=sget('phone','s');
            if(!is_mobile($mobile)){
                $this->error('手机号码不正确');
            }

            $sms=M('system:sysSMS');
            
            //请求动态码
            $result=$sms->genDynamicCode($mobile);
            if($result['err']>0){ //请求错误
                $this->error($result['msg']);
            }
            
            $msg=$result['msg']; //短信内容
            //发送手机动态码
            $sms->send(0,$mobile,$msg,2);
            $this->success('发送成功');
        }
}