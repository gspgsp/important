<?php
/**
*塑料圈控制器-gsp
*/
class plasticAction extends homeBaseAction
{
	public static $code;
	public static $AppID;
	public static $AppSecret;
	public static $signature;
	public static $timestamp;
	public static $noncestr;
	public static $head_img_wx;
	public static $openid;
	public static $access_token;
	protected $pointsGoodsModel;
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common');
        $this->pointsGoodsModel = M('points:pointsGoods');
		//微信授权
		$this->AppID = 'wx2cfaa7723ce834e9';
		$this->AppSecret = '141cb0a0a49efc54d29dd624a8355c8a';
//         $this->AppID = 'wxbe66e37905d73815';
//         $this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
//         $get = $_GET['param'];
		$code = $_GET['code'];
//         $cache = cache::startMemcache();
// //         $sid=$GLOBALS['CORE_SESS']->getSid();
//         if(!empty($code)){
//             $open_access = $this->get_author_access_token($code);
//             $cache->set('open_access',$open_access,7200);
//             $info=$this->get_user_info($open_access['openid'],$open_access['access_token']);
//             $this->openid = $open_access['openid'];
//             if(!empty($info)){
//                 $cache->set('weixinAuth'.$this->openid,$info,7200);//保存用户信息
//                 $this->head_img_wx = $info['headimgurl'];
//             }else{
//                 $cache->delete('weixinAuth'.$this->openid);
//                 $cache->delete('weixinAuth'.$sid);
//                 $cache->delete('open_access');
//                 $this->get_authorize_url("http://m.myplas.com/plasticzone/plastic",'STATE');
//             }
//         }
	}
	//进入首页
	public function init(){
	 $cache = cache::startMemcache();
	 $code = $_GET['code'];
//             if(empty($code)){
//                  $this->get_authorize_url("http://m.myplas.com/plasticzone/plastic",'STATE');
//             }else{
//                 $open_access = $this->get_author_access_token($code);
//                 $cache->set('open_access',$open_access,7200);
//                 $info=$this->get_user_info($open_access['openid'],$open_access['access_token']);
//                 $this->openid = $open_access['openid'];
//                 if(!empty($info)){
//                     $cache->set('weixinAuth'.$this->openid,$info,7200);//保存用户信息
//                     $this->head_img_wx = $info['headimgurl'];
//                 }else{
// //                     $cache->delete('weixinAuth'.$this->openid);
// //                     $cache->delete('weixinAuth'.$sid);
// //                     $cache->delete('open_access');
// //                     $this->get_authorize_url("http://m.myplas.com/plasticzone/plastic",'STATE');
//                 }
//             }
		//
//         $time = time();
//         $signPackage = array(
//             "jsapi_ticket" => $this->get_jsapi_ticket(),
//             "noncestr"  => $this->createNonceStr(),
//             "timestamp" => $time,
//             "url"       => 'http://m.myplas.com/plasticzone/plastic#!/index',
//         );
		
		
		
//         $string = $this->formatQueryParaMap($signPackage, false);
//         $this->timestamp = $time;
//         $this->signature = sha1($string);
//         $this->noncestr = $this->createNonceStr();
//         $this->AppID = 'wxbe66e37905d73815';
		   $SignPackage= $this->getSignPackage();
		   $this->timestamp = $SignPackage['timestamp'];
		   $this->signature = $SignPackage['signature'];
		   $this->noncestr = $SignPackage['nonceStr'];
		   $this->AppID = $SignPackage['appId'];
		//
		$this->display('index');
	}
	public function plasticzone2(){
		$this->display('plasticzone2/index');
	}
	//塑料头条调用接口方法，勿动,千万不要动，动一次打一次，动一次打一次，动次打次，动次大次。。。
	public function headlineApi(){
		$SignPackage= $this->getSignPackage();
		$arr=array();
		$arr['timestamp'] = $SignPackage['timestamp'];
		$arr['signature'] = $SignPackage['signature'];
		$arr['noncestr'] = $SignPackage['nonceStr'];
		$arr['AppID'] = 'wx2cfaa7723ce834e9';
		return $arr;
	}
	//获取首页数据
	public function getPlasticPerson(){
		$this->is_ajax = true;
		//a b c d e f ...获取联系人
		$letter = sget('letter','s');
		//搜素关键字
		$keywords = sget('keywords','s');

		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$data = M('plasticzone:plasticPerson')->getPlasticPerson($this->user_id,$letter,$keywords,$page,$size);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关数据'));
		$this->_checkLastPage($data['count'],$size,$page);
		if($page == 4 && $this->user_id<=0) $this->json_output(array('err'=>1,'msg'=>'想要查看更多，请登录','count'=>$data['count']));
		$this->json_output(array('err'=>0,'persons'=>$data['data'],'count'=>$data['count']));
	}
	//获取塑料圈的塑料产品-首页
	public function getPlasticNews(){
		$this->is_ajax = true;
		$extendType = sget('extendType','i',1);//1 塑料头条 2 塑料代采 3 抢单神器 4 团购
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$cateId = sget('cateId','i',0);//某类(给值)
		$item = $cateId>0 ? 0 : 1;//是否限制
		$result = M('plasticzone:plasticExtAbility')->getNewsCate($extendType,$item,$page,$size,$cateId);
		$this->json_output($result);
	}
	//获取塑料圈的塑料产品-详情页
	public function getPlasticNewsDetail(){
		$this->is_ajax = true;
		$id = sget('id','i',0);//某条id
		$cateId = sget('cateId','i',0);//所属类
		$pre = sget('pre','i',0);//上一页1
		$nex = sget('nex','i',0);//下一页2
		if($pre == 1) {
			$where = "cate_id = $cateId and id < $id";
			$order = "id desc";
			$id = $this->getPlasticNewsId($where,$order);
		}elseif ($nex == 2) {
			$where = "cate_id = $cateId and id > $id";
			$order = "id asc";
			$id = $this->getPlasticNewsId($where,$order);
		}
		$result = M('plasticzone:plasticExtAbility')->getItemsByCateId($id,1);
		$this->json_output($result);
	}
	//返回塑料圈的塑料产品-详情页id
	public function getPlasticNewsId($where,$order){
		$tarid = $this->db->model('news_content')->select('id')->where($where)->order($order)->limit('0,1')->getOne();
		if($tarid >0) return $tarid;
		$this->json_output(array('err'=>3,'msg'=>'没有上一条或下一条数据'));
	}
	//获取塑料圈总人数
	public function getAllMembers(){
		$this->is_ajax = true;
		$count = M('plasticzone:plasticPersonalInfo')->getAllMembers();
		if($count>0) $this->json_output(array('err'=>0,'count'=>$count));
		$this->json_output(array('err'=>0,'count'=>0));
	}
	//获取供求发布和消息回复
	public function getReleaseMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		//筛选条件
		$keywords = sget('keywords','s');
		$type = sget('type','i',0);//0 全部 1 求购 2 供给
		//普通条件
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$data = M('plasticzone:plasticRelease')->getReleaseMsg($keywords,$page,$size,$type);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data']));
	}
	//删除单条回复
	public function deleteRepeat(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$id = spost('id','i',0);
		$result = M('plasticzone:plasticMyMsg')->deleteRepeat($id);
		$this->json_output($result);
	}
	//回复消息
	public function saveMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		// $user_id = sget('user_id','i',0)//回复人id
		$pur_id = sget('pur_id','i',0);//purchase表的消息id
		$send_id = sget('send_id','i',0);//purchase表发报价或采购人的(pur.user_id)
		$content = sget('content','s');//回复的内容
		$result = M('plasticzone:plasticRepeat')->saveMsg($this->user_id,$pur_id,$send_id,$content);
		if($result) $this->json_output(array('err'=>0,'msg'=>'回复消息保存成功'));
	}
	//获取我的供给或求购
	public function getMyMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$page = sget('page','i',1);
		$size = sget('size','i',10);

		$type = sget('type','i');//1采购 2报价
		$data = M('plasticzone:plasticMyMsg')->getMyMsg($this->user_id,$page,$size,$type);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data']));
	}
	//分享我的供给或其求购
	public function shareMyPur(){
		$this->is_ajax = true;
		// if($this->user_id<=0) $this->error('账户错误');
		$id = sget('id','i');
		$data = M('plasticzone:plasticShare')->getMySharePur($id);
		if(empty($data)) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		$this->json_output(array('err'=>0,'data'=>$data));
	}
	
	//验证是否分享成功日志
	public function saveShareLog(){
		$share_id = sget('id','i');
		$type = sget('type','i',1);//分享类容类型  1采购 2报价
		$user_id = sget('user_id','i');//分享人的id
		$share = intval($this->plastic_points['share']);
		M('plasticzone:plasticShare')->saveShareLog($share_id,$type,$user_id,$share);
	}
	//删除我的供给或求购
	public function deleteMyMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$id = sget('id','i');//当前我的报价或采购的id
		$result = M('plasticzone:plasticMyMsg')->deleteMyMsg($id);
		$this->json_output($result);
	}
	//获取我的消息(留言)
	public function getMyComment(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$data = M('plasticzone:plasticMyMsg')->getMyComment($this->user_id,$page,$size);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		if(empty($data['data']) && $page >1) $this->json_output(array('err'=>3,'msg'=>'没有相关的数据'));
		// $this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data']));
	}
	//获取我的引荐(引荐数)
	public function getMyIntroduction(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$data = M('plasticzone:plasticIntroduction')->getMyIntroduction($page,$size);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据','count'=>0));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data'],'count'=>$data['count']));
	}
	//获取我的粉丝和我的关注(数)
	public function getMyFuns(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$type = sget('type','i');//1粉丝2关注
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$data = M('plasticzone:plasticIntroduction')->getMyFuns($this->user_id,$type,$page,$size);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据','count'=>0));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data'],'count'=>$data['count']));
	}
	//获取我的供给数量
	public function getSaleCount(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$type = sget('type','i');//1 求购 2 报价
		$s_b_count = M('plasticzone:plasticPersonalInfo')->getConut($this->user_id,$type);
		$s_b_count = empty($s_b_count)?0:$s_b_count;
		$this->json_output(array('err'=>0,'s_b_count'=>$s_b_count));
	}
	//获取留言数(同下)
	//获取引荐数(同上)
	//获取粉丝-关注数(同上)
	//获取我的积分
	public function getMyPoints(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
		$points = empty($points)?0:$points;
		$this->json_output(array('err'=>0,'points'=>$points));
	}
	//我的积分首页
	public function getMyPointsDetail(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
        $gtype = sget('gtype','i');//1=>'家居',2=>'数码',空，全部
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
        if(!$result = M('touch:creditshop')->getCreditShop($gtype))  $this->json_output(array('err'=>2,'msg'=>'没有相关的数据!'));
            foreach ($result as &$v) {
                $v['thumb']=FILE_URL."/upload/".$v['thumb'];
            }
            $this->json_output(array('err'=>0,'points'=>$points,'shop'=>$result));
	}
	//积分-商品详情页
	public function getCreditdetail(){
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
        $gid = sget('gid','i',0);
        $result = M('touch:creditshop')->getShopDetail($gid);
        $result['image']=FILE_URL."/upload/".$result['image'];
        $result['thumb']=FILE_URL."/upload/".$result['thumb'];
        $this->json_output($result);
	}
	//要置顶的5供求信息
	public function getTopPur(){
		$this->is_ajax=true;
        if($this->user_id<=0) $this->error('账户错误');
        $top_type = sget('topType','i',1);//置顶类型 0是通讯录置顶 1供求信息置顶
        //pc 用参数
        $type = sget('type','i',1);//1采购 2报价
        $userid = sget('userid','i',0);//ta的id
        if($top_type == 1){
        	if($userid > 0){
        		$where = "pur.sync=6 and pur.user_id=$userid and pur.type = $type";
        	}else{
        		$where = "pur.sync=6 and pur.user_id={$this->user_id}";
        	}
        	$data = $this->db->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from('purchase pur')
            ->leftjoin('product pro','pur.p_id=pro.id')
            ->leftjoin('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->order('pur.input_time desc')
            ->limit('0,5')
            ->getAll();
            foreach ($data as &$value) {
            	$value['input_time'] = date("m-d H:i",$value['input_time']);
            	//显示的内容
	            if(empty($value['content'])){
	                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
	                    $value['contents'] = '';
	                }else{
		                $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
		                $value['contents'] = $userid>0?$value['contents']:mb_substr($value['contents'], 0,30,'utf-8').'...';
	                }
	            }elseif(!empty($value['content'])){
	                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
	                    $value['contents'] = $userid>0?$value['content']:mb_substr($value['content'], 0,30,'utf-8').'...';
	                }else{
	                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
	                    $value['contents'] = $userid>0?$value['contents']:mb_substr($value['contents'], 0,30,'utf-8').'...';
	                }
	            }
            }
            if(!empty($data))  $this->json_output(array('err'=>0,'data'=>$data));
           	$this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
        }
        $this->json_output(array('err'=>3,'msg'=>'不在选择范围内'));
	}
	//兑换商品
	public function addOrder(){
		if($_POST){
            $this->is_ajax=true;
            if($this->user_id<=0) $this->error('账户错误');
            // $data=saddslashes($_POST);
            // foreach ($data as $key => $value) {
            //     if(empty($value)) $this->json_output(array('err'=>2,'msg'=>'信息不完整'));
            // }
            $data = $_POST;
            //分类orderType 1 实物 2 虚拟(两类置顶)
            if($data['orderType'] == 1){
            	//检查验证码
	            $resVcode=M('system:sysSMS')->chkDynamicCode($data['phone'],$data['vcode']);
	            if($resVcode['err']>0){
	                $this->json_output(array('err'=>3,'msg'=>$resVcode['msg']));
	            }
	            if(!is_mobile($data['phone'])) $this->json_output(array('err'=>4,'msg'=>'错误的联系电话'));
	            $uinfo=M('public:common')->model('contact_info')->where("user_id=$this->user_id")->getRow();
	            $id=$data['gid'];//奖品id
	            if(!$goods=$this->pointsGoodsModel->getPk($id)) $this->json_output(array('err'=>5,'msg'=>'没有找到您要兑换的'));
	            if($goods['cate_id'] == 7){
	            	$res = $this->plastic_ac_start($goods['cate_id']);//活动产品
		            if($res == 1 || $res == 2) $this->json_output(array('err'=>16,'msg'=>'不好意思，活动还没开始或已经结束咯'));
		            $convert = $this->is_allow_change($goods);//活动兑换条件
		            if($convert == 0) $this->json_output(array('err'=>17,'msg'=>'不好意思，兑换条件不满足'));
	            }
	            if($goods['status']==2) $this->json_output(array('err'=>6,'msg'=>'您兑换的商品已下架'));
	            if($goods['num']<=0) $this->json_output(array('err'=>7,'msg'=>'您兑换的商品库存不足'));
	            if($uinfo['quan_points']<$goods['points']) $this->json_output(array('err'=>8,'msg'=>'您的积分不足'));
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
	            //开启事物
	            $model->startTrans();
	            try {
	                if(!$model->add($_orderData)) throw new Exception('系统错误，无法兑换。code:101');
	                if(!$billModel->decPoints($goods['points'], $this->user_id, 5, 1,$goods['id'])) throw new Exception('系统错误，无法兑换。code:102');
	            } catch (Exception $e) {
	                $model->rollback();
	                $this->json_output(array('err'=>18,'msg'=>$e->getMessage()));
	            }
	            //事物提交
	            $model->commit();
	            $this->success('兑换成功');
            }elseif ($data['orderType'] == 2) {
            	$time_s = 0;
            	$time_e = 0;
            	$uinfo=M('public:common')->model('contact_info')->where("user_id=$this->user_id")->getRow();
	            $id=$data['gid'];//奖品id
	            $top_type = $data['topType'];//置顶类型 0是通讯录置顶 1供求信息置顶
	            $time_type = $data['timeType'];//置顶时间类型 0 分钟 1 天
	            $pur_id = $data['pid'];//置顶供求信息的id topType==1 的时候才有值
	            $today = strtotime(date('Y-m-d',time()));
	            if($time_type == 0){
	            	$time_s = $today+$data['timeStar'];//开始时间戳
	            	$time_e = $today+$data['timeStar']+3599;//结束时间戳
	            	// $time_s = $today+$data['timeStar'];//开始时间戳
	            	// $time_e = $today+$data['timeEnd'];//结束时间戳
	            }elseif ($time_type == 1) {
	            	if($data['timeCon'] < $today) $this->json_output(array('err'=>15,'msg'=>'时间过期啦'));
	            	$time_s = $data['timeCon'];
	            	$time_e = $data['timeCon']+86399;
	            }
	            $plasticCron = M('plasticzone:plasticCron');
	            if(!$plasticCron->is_allow_set($time_s,$time_e,$time_type)) $this->json_output(array('err'=>10,'msg'=>'该时间段已被选择啦'));
	            if(!$goods=$this->pointsGoodsModel->getPk($id)) $this->json_output(array('err'=>11,'msg'=>'没有找到您要兑换的'));
	            if($goods['status']==2) $this->json_output(array('err'=>12,'msg'=>'您兑换的商品已下架'));
	            if($goods['num']<=0) $this->json_output(array('err'=>13,'msg'=>'您兑换的商品库存不足'));
	            if($uinfo['quan_points']<$goods['points']) $this->json_output(array('err'=>14,'msg'=>'您的积分不足'));
	            $model = M('points:pointsOrder');
	            $billModel=M('points:pointsBill');
	            $_orderData = array(
	                'status' => 1,
	                'create_time'   => CORE_TIME,
	                'order_id'      => $this->buildOrderId(),
	                'goods_id'      => $goods['id'],
	                'uid'           => $this->user_id,
	                'usepoints'     => $goods['points'],
	                'remark'=>trim($data['remark']),
	            );
	            $_cronData = array(
	            	'user_id'=>$this->user_id,
	            	'type'=>$top_type,
	            	'exe_time_s'=>$time_s,
	            	'exe_time_e'=>$time_e,
	            	'input_time'=>CORE_TIME,
	            	'status_s'=>0,
	            	'status_e'=>0,
	            	'purchase'=>$top_type==1?$pur_id:0,
	            	);
	            //开启事物
	            $model->startTrans();
	            try {
	            	if(!$plasticCron->add($_cronData)) throw new Exception('系统错误，无法兑换。code:103');
	                if(!$model->add($_orderData)) throw new Exception('系统错误，无法兑换。code:101');
	                if(!$billModel->decPoints($goods['points'], $this->user_id, 5)) throw new Exception('系统错误，无法兑换。code:102');
	            } catch (Exception $e) {
	                $model->rollback();
	                $this->json_output(array('err'=>18,'msg'=>$e->getMessage()));
	            }
	            //事物提交
	            $model->commit();
	            $this->success('兑换成功');
            }
        }

	}
	//判断活动是否开始
	public function plastic_ac_start($cate_id){
		$today = strtotime(date('Y-m-d H:00:00',time()));
		$start = 1479693600;
		$end = 1480471200;
		if($today < $start && $cate_id == 7) return 1;
		if($today > $end && $cate_id == 7) return 2;
		return 3;
	}
	//查看用户是否满足活动兑换的要求
	public function is_allow_change($arr){
			$mobile = $_SESSION['uinfo']['mobile'];
			$count = $this->db->model('customer_contact')->where("parent_mobile=$mobile")->select('count(user_id)')->getOne();
			$bill = $this->db->model('points_bill')->where("uid = {$this->user_id} and gid = {$arr['id']}")->select('count(id)')->getOne();
			switch ($arr['price']) {
				case 10:
					if($count >= 3 && $bill < 2){
						return 10;
					}
					return 0;
				case 30:
					if($count >= 8){
						return 30;
					}
					return 0;
				case 50:
					if($count >= 12){
						return 50;
					}
					return 0;
				case 100:
					if($count >= 16){
						return 100;
					}
					return 0;
				case 200:
					if($count >= 25){
						return 200;
					}
					return 0;
				default:
					return 0;

			}
	}
	//生产订单号
    protected function buildOrderId(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    //塑料圈-兑换记录
    public function getCreditRecord(){
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$result = M('touch:creditRecord')->getCreditRecord($this->user_id,1);
        foreach ($result as  &$v) {
            $v['thumb']=FILE_URL."/upload/".$v['thumb'];
            $v['create_time'] = date('Y-m-d',$v['create_time']);
            $v['update_time'] = $v['update_time']==0?'-':date('Y-m-d',$v['update_time']);
            $v['status'] = L('points_status')[$v['status']];
        }
        if(empty($result)) $this->json_output(array('err'=>2,'msg'=>'没有相关兑换记录'));
		$this->json_output(array('err'=>0,'record'=>$result));
    }
    //塑料圈-积分明细
    public function getPlasticCreditDetail(){
    	$this->is_ajax = true;
        if($this->user_id<=0) $this->error('账户错误');
		$points = M('points:pointsBill')->getUerPoints($this->user_id);
		$result = M('touch:creditdetail')->getCreditDetail($this->user_id);
		foreach ($result as &$value) {
			$value['type'] = L('cre_detail_type')[$value['type']];
			$value['addtime'] = date('Y-m-d H:i:m',$value['addtime']);
		}
		$this->json_output(array('err'=>0,'points'=>$points,'detail'=>$result));
    }
	//获取未读消息数/留言数
	public function getMsgCount(){
		$this->is_ajax = true;
		$type = sget('type','i',2);//1 留言 2 消息 3...
		$count = M('plasticzone:plasticMsgCount')->getMsgCount($this->user_id,$type);
		if($this->user_id>0 && $count>0) $this->json_output(array('err'=>0,'count'=>$count));
		$this->json_output(array('err'=>0,'count'=>0));

	}
	//查看塑料圈好友资料
	public function getZoneFriend(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$userid = sget('userid','i');//当前联系人的id
		$data = M('plasticzone:plasticPersonalInfo')->getPersonalInfo($this->user_id,$userid);
		if(empty($data)) $this->json_output(array('err'=>2,'msg'=>'没有相关资料'));
		$this->json_output(array('err'=>0,'data'=>$data));
	}
	//获取我的塑料圈
	public function getMyPlastic(){
		$cache = cache::startMemcache();
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$headimgurl = sget('headimgurl','s');
		$data = M('plasticzone:plasticPersonalInfo')->getMyPlastic($this->user_id,$headimgurl);
		if(empty($data)) $this->json_output(array('err'=>2,'msg'=>'没有相关资料'));
		$this->json_output(array('err'=>0,'data'=>$data));
	}
	//查看我的资料
	public function getSelfInfo(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$data = M('plasticzone:plasticPersonalInfo')->getSelfInfo($this->user_id);
		if(empty($data)) $this->json_output(array('err'=>2,'msg'=>'没有相关资料'));
		$this->json_output(array('err'=>0,'data'=>$data));
	}
	//保存我的资料
	public function saveSelfInfo(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$type = sget('type','i');//类型 1 地址 2 性别 3 主营牌号
		$field = sget('field','s');
		$result = M('plasticzone:plasticSave')->saveSelfInfo($this->user_id,$type,$field);
		if(empty($result)) $this->json_output(array('err'=>2,'msg'=>'保存资料失败'));
		$this->json_output(array('err'=>0,'msg'=>'保存资料成功'));
	}
	//获取ta的求购或供给
	public function getTaPur(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$keywords = sget('keywords','s');//这里传空值
		$page = sget('page','i',1);
		$size = sget('size','i',10);

		$userid = sget('userid','i');//当前联系人的id
		$type = sget('type','i');//1采购 2报价

		$data = M('plasticzone:plasticRelease')->getReleaseMsg2($keywords,$page,$size,$userid,$type);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data']));
	}
	//关注或取消关注
	public function focusOrCancel(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$focused_id = sget('focused_id','i');//当前联系人的id
		$result = M('plasticzone:plasticAttention')->getAttention($this->user_id,$focused_id);
		$this->json_output($result);
	}
	//判断用户是否登录
	public function isUserLogin(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$this->success('用户已登录');
	}
	//登录时计算会员等级
	public function getMemberLevel(){
		$this->is_ajax = true;
		M('plasticzone:plasticPersonalInfo')->getMemberLevel($this->user_id);
		$this->success('会员等级更新成功');
	}
	//塑料圈联系人的-发送消息
	public function sendZoneContactMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$userId = sget('userId','i');//接受消息人的id
		$content = sget('content','s');
		$result = M('plasticzone:plasticRepeat')->saveZoneMsg($this->user_id,$userId,$content);
		if($result) $this->json_output(array('err'=>0,'msg'=>'回复消息保存成功'));
	}
	//塑料圈联系人的-我的消息
	public function getZoneContactMsg(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$page = sget('page','i',1);
		$size = sget('size','i',10);
		$type = sget('type','i',1);//1:我接受的 2:我发送的
		$data = M('plasticzone:plasticMyMsg')->getZoneContactMsg($this->user_id,$type,$page,$size);
		if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
		$this->_checkLastPage($data['count'],$size,$page);
		$this->json_output(array('err'=>0,'data'=>$data['data']));
	}
	//偏好设置-发送短信
	public function favorateSet(){
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		$type = sget('type','i');//0 关注 1 回复
		$is_allow = sget('is_allow','i',0);//0:允许 1:不允许
		$result = M('plasticzone:plasticAttention')->favorateSet($this->user_id,$type,$is_allow);
		$this->json_output($result);
	}
	//退出登录/mobi/personalCenter/logOut
	//获取我的资料
	//获取我的积分
	//判断是否到最后一页
	private function _checkLastPage($count,$size,$page){
		if($count>0){
			if($count%$size==0 && ceil($count/$size)<$page){
				$this->json_output(array('err'=>3,'msg'=>'没有更多数据'));
			}elseif ($count%$size!=0 && ceil($count/$size)<$page) {
				$this->json_output(array('err'=>3,'msg'=>'没有更多数据'));
			}
		}
	}
	//http-->curl
	protected function http($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$output = curl_exec($ch);//输出内容
		curl_close($ch);
		return $output;
	}
	//通过回调方法获取用户的code
	protected function get_authorize_url($redirect_uri = '', $state = ''){
	   $redirect_uri = urlencode($redirect_uri);
	   $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->AppID}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
	   echo "<script language='javascript' type='text/javascript'>";
	   echo "window.location.href='$url'";
	   echo "</script>";
   }
	//获取授权的token
	protected function get_author_access_token($code=''){
		if($code == '') return false;
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->AppID}&secret={$this->AppSecret}&code={$code}&grant_type=authorization_code";
		$result = $this->http($url);
		if( $result ) $result = json_decode($result, true);
		if( isset($result['errcode']) ){
			return false;
		}else{
			return $result;
		}

	}
	//通过openid 和 token获取用户信息
	protected function get_user_info($openid,$access_token){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		$result = json_decode($this->http($url), true);
		if(!isset($result['errcode']))
		{
			return $result;
		}else
		{
			return false;
		}
	}
	//curl 获取文件数据
	public function curl_file($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);//只取body头
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec执行成功后返回执行的结果；不设置的话，curl_exec执行成功则返回true
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	/**
	 * @param $serverId jssdk文件上传返回的serverId
	 * @return string
	 */
	public  function savePicToServer() {
		$this->is_ajax = true;
		if($this->user_id<=0) $this->error('账户错误');
		A('public:upload')->saveCardImg($savePath,2);
		// $accessToken =$this->wx_get_token();
		// $serverId=spost('serverId','s','');
		// $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken}&media_id={$serverId}";
		// $result = $this->curl_file($url);
		// if (!file_exists(ROOT_PATH.'../static/myapp/weixin/')){
		// 	mkdir(ROOT_PATH.'../static/myapp/weixin/');
		// }
		// if(empty($result)) $this->json_output(array('err'=>2,'msg'=>'请求失败'));
		// $timestamp = time();
		// $savePathFile = ROOT_PATH.'../static/myapp/weixin/'.$timestamp.'.jpg';
		// $ret = file_put_contents($savePathFile, $result, true);
		// //获取资源进行裁剪
		// $this->reImg($savePathFile);
		// $data = M('plasticzone:plasticPersonalInfo')->updatethumbqq($this->user_id,FILE_URL.'/myapp/weixin/'.$timestamp.'.jpg');
		// $this->json_output(array('err'=>0,'msg'=>'图片上传成功'));
	}
	//保存名片到服务器
	public function saveCardImg(){
		$this->is_ajax=true; //指定为Ajax输出
		$result=A('public:upload')->saveCardImg($savePath,1);
	}
// 	/**
// 	 *分享
// 	 */
// 	//获取token
// 	protected function wx_get_token(){
// 		$_key='weixin_access_plastic_token';
// 		$data = json_decode(file_get_contents($tokenFile));
// 		if ($data->expire_time < time() or !$data->expire_time);
// 		$cache=cache::startMemcache();
// 		$access_token=$cache->get($_key);
// 		if(empty($access_token)){
// 			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
// 			$result = json_decode($this->http($url), true);
// 			if(isset($result['access_token'])){
// 				$access_token = $result['access_token'];
// 				$cache->set($_key,$access_token,7000);
// 				return $access_token;
// 			}else{
// 				return false;
// 			}
// 		}else{
// 			return $access_token;
// 		}
// 	}
	/**
	 *分享
	 */
	//获取token
	private function wx_get_token() {
		$tokenFile = "access_token.txt";//缓存文件名
		$data = json_decode(file_get_contents($tokenFile),true);
			if ($data['expire_time'] < CORE_TIME) {
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
				$res = json_decode($this->http($url), true);
				$access_token = $res['access_token'];
				if($access_token) {
					$data['expire_time'] = CORE_TIME + 7000;
					$data['access_token'] = $access_token;
					$fp = fopen($tokenFile, "w");
					fwrite($fp, json_encode($data));
					fclose($fp);
				}
				return $access_token;
			} else {
			   	return $data['access_token'];
			}
	}
	//生成随机字符串
	protected function createNonceStr($length = 16){
	  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  $str = "";
	  for($i = 0; $i < $length; $i++){
		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	  }
	  return $str;
	}
	//获取票据
	protected function get_jsapi_ticket(){
		$_key='weixin_jsapi_ticket';
		$cache=cache::startMemcache();
		$ticket=$cache->get($_key);
		if(empty($ticket)){
			$access_token = $this->wx_get_token();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
			$result = json_decode($this->http($url), true);
			if(isset($result['ticket'])){
				$ticket = $result['ticket'];
				$cache->set($_key,$ticket,7000);
				return $ticket;
			}else{
				return false;
			}
		}else{
			return $ticket;
		}
	}
	//格式化输出字符串
	protected function formatQueryParaMap($paraMap, $urlencode=false){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
		   if (null != $v && "null" != $v && "sign" != $k) {
			   if($urlencode){
				 $v = urlencode($v);
			  }
			  $buff .= $k . "=" . $v . "&";
		   }
		}
		$reqPar;
		if (strlen($buff) > 0) {
		   $reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	//获取url
	protected function get_url(){
	 $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	 $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	 return $url;
	}
    //获取签名
    // public function getSignPackage(){
    //     $this->is_ajax = true;
    //     $signPackage = array(
    //         "jsapi_ticket" => $this->get_jsapi_ticket(),
    //         "noncestr"  => $this->createNonceStr(),
    //         "timestamp" => time(),
    //         "url"       => $this->get_url(),
    //     );
    //     $string = $this->formatQueryParaMap($signPackage, false);
    //     $signPackage['signature'] = sha1($string);
    //     $signPackage['appId'] = $this->AppID;
    //     $this->json_output(array('err'=>0,'signPackage'=>$signPackage));
    //     return $signPackage;
    // }
	public function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();
	
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
	
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	
		$signature = sha1($string);
	
		$signPackage = array(
			"appId"     => $this->AppID,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}
	//获取票据
	private function getJsApiTicket(){
		$_key='weixin_jsapi_ticket';
		$cache=cache::startMemcache();
		$ticket=$cache->get($_key);
		if(empty($ticket)){
			$access_token = $this->wx_get_token();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
			$result = json_decode($this->http($url), true);
			if(isset($result['ticket'])){
				$ticket = $result['ticket'];
				$cache->set($_key,$ticket,7000);
				return $ticket;
			}else{
				return false;
			}
		}else{
			return $ticket;
		}
	}
	//处理图片的压缩问题
	private function reImg($source_path, $target_width=200, $target_height=200){
	$source_info   = getimagesize($source_path);
	$source_width  = $source_info[0];
	$source_height = $source_info[1];
	$source_mime   = $source_info['mime'];
	$source_ratio  = $source_height / $source_width;
	$target_ratio  = $target_height / $target_width;
	if ($source_ratio > $target_ratio){
		$cropped_width  = $source_width;
		$cropped_height = $source_width * $target_ratio;
		$source_x = 0;
		$source_y = ($source_height - $cropped_height) / 2;
	}elseif ($source_ratio < $target_ratio){
		$cropped_width  = $source_height / $target_ratio;
		$cropped_height = $source_height;
		$source_x = ($source_width - $cropped_width) / 2;
		$source_y = 0;
	}else{
		$cropped_width  = $source_width;
		$cropped_height = $source_height;
		$source_x = 0;
		$source_y = 0;
	}
	switch ($source_mime){
		case 'image/gif':
	$source_image = imagecreatefromgif($source_path);
		break;
		case 'image/jpeg':
	$source_image = imagecreatefromjpeg($source_path);
		break;
		case 'image/png':
	$source_image = imagecreatefrompng($source_path);
		break;
		default:
		return false;
		break;
	}
		$target_image  = imagecreatetruecolor($target_width, $target_height);
		$cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
		imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
		imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
		imagejpeg($target_image,$source_path,90);
		imagedestroy($source_image);
		imagedestroy($target_image);
		imagedestroy($cropped_image);
	}


}