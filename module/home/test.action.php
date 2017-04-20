<?php
	class testAction extends homeBaseAction{
		protected $db;
		public function __init(){
			ini_set('display_errors', 1);
			$this->db=M('public:common');
		}
		 public function test(){
		 	$mem_time =strtotime(date('Ymd')) + 86400-time();
//		 	p($mem_time);
		 	// phpinfo();
			// $this->display('test.html');
		 }
//		 public function update3(){
//		 	set_time_limit(0);
//		 	$info = $this->db->model('info')->getAll();
//		 	foreach ($info as $k => $v) {
//		 		$content = preg_replace('/height=\"[\d]*?\"/','',$v['content']);
//		 		$this->db->model('info')->where("id = {$v['id']}")->update(array('content'=>saddslashes($content)));
//		 	}
//		 }
		// public function test2(){
		// 	set_time_limit(0);
		// 	for($i=0; $i<2000; $i++){
		// 		$url="http://www.manmanbuy.com/list_{$i}.aspx";
		// 		$opts = array(
		// 		  	'http'=>array(
		// 		    	'method'=>"GET",
		// 		    	'header'=>"Accept-language: en\r\n" .
		// 		            "Cookie: foo=bar\r\n".
		// 		            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0\r\n"
		// 		  )
		// 		);
		// 		$context = stream_context_create($opts);
		// 		$html = iconv("GB2312//IGNORE","UTF-8", file_get_contents($url,false, $context));
		// 		if($html){
		// 			if(preg_match_all("/根据你的需求筛选([\s\S]+?)进行比价/", $html,$matches)){
		// 				$temp = array();
		// 				unset($matches[0]);
		// 				$cate_name = trim($matches[1][0]);
		// 				$items = $this->db->model('cate')->where("`cate_name`= '$cate_name'")->getRow();
		// 				if(empty($items)){
		// 					$temp['cate_name'] = $cate_name;
		// 					$temp['url'] = saddslashes($url);
		// 					$temp['cate_id'] = $i;
		// 					$temp['input_time'] = CORE_TIME;
		// 					$this->db->model('cate')->add($temp);
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		// // 开户账号加密
		// public function jiami(){
		// 	set_time_limit(0);
		// 	$list = $this->db->model('contact_info')->select('user_id')->getCol();
		// 	// $count = count(array_unique($list));
		// 	// p($count);
		// 	foreach ($list as $k => $v) {
		// 	// 	//查询
		// 		$info =$this->db->model('customer_contact')->where("user_id = $v")->getRow();
		// 		if(empty($info)){
		// 			 $this->db->model('customer_contact')->where("user_id = $v")->delete();
		// 		}

		// 	}
		// }
		//
//		public function test(){
//			$arr = array();
//			$data = $this->db->model('in_log')->where("(`remainder` -  `lock_number`) > 0")->getAll();
//			foreach ($data as $k => $v) {
//				$gl= $this->db->model('order')->select('o_id')->where("join_id = {$v['o_id']} or store_o_id = {$v['o_id']}")->getOne();
//				if($gl){
//					$data_gl =  $this->db->model('sale_log')->where("o_id = $gl and p_id = {$v['p_id']}")->getRow();
//					if($data_gl){
//						$arr[] = $v['id'];
//					}
//				}
//			}
//			p($arr);die;
//		}
		// public function up(){
		// 	// $data = $this->db->model('temp_qqimg')->getAll();
		// 	// foreach ($data as $k => $v) {
		// 	// 	$user = $this->db->model('customer_contact')->select('user_id')->where("mobile = '{$v['mobile']}'")->getOne();
		// 	// 	$this->db->model('contact_info')->where("`user_id` = $user")->update(array('thumbqq'=>$v['qq_image']));
		// 	// }
		// }
		//裁剪图片
//		public function t(){
//			$str = '{"expire_time":1475063772,"access_token":"UI5p0Lg1BGKt5jP0KlztiRwaIK9Qy0gXcFk0qnv55ZDbBTtgzVPoTj86Wte2B0Gd0tfSilwYEGUH-BQw31cV9TWqdRVOmugPWLNaDKJJfqGrAfRj7KrduNipvTpxS9gBAVOeADAEZS"}';
//			p(json_decode($str,true));
//		}
		//采集原油和布伦特价格
		// public function cj(){
		// 	set_time_limit(0);
		// 	$url="http://shop.m.taobao.com/shop/coupon.htm?seller_id=1660340503&activity_id=f3ea38fb2ab845e382544edf0987b5ee";
		// 	$opts = array(
		// 		'http'=>array(
		// 			'method'=>"GET",
		// 			'header'=>"Accept-language: en\r\n" .
		// 				"Cookie: foo=bar\r\n".
		// 				// "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0\r\n".
		// 				"User-Agent:Mozilla/5.0 (Linux; U; Android 2.2; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1\r\n".
		// 				"HTTP_ACCEPT:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		// 	  )
		// 	);
		// 	$context = stream_context_create($opts);
		// 	$html = file_get_contents($url,false, $context);
		// 	if($html){
		// 		if(preg_match_all("/<dd>([\s\S]+?)<\/dd>/", $html, $matches)){
		// 			$matches[1][0] = str_replace(array("\r\n", "\r", "\n"," "), "",strip_tags($matches[1][0]));
		// 			p($matches[1]);die;
		// 		}
		// 	}
		// }
		//塑料圈每日报表
		public function report(){
			$s =sget('s','i',strtotime('-1 day'));
			$e =sget('e','i',time());
			$s1 =sget('s1','i',strtotime('-2 day'));
			$e1 =sget('e1','i',strtotime('-1 day'));
			//登录
			echo "<font style='color:red;'>s  为开始时间 e 为结束时间  s1为次日开始时间 e1为次日结束时间</font></br></br>";
			$act_user = $this->db->model('log_login')->select('user_id')->where("`input_time` > $s  and `input_time` <  $e and chanel = 6")->getCol();
			$act_user_next = $this->db->model('log_login')->select('user_id')->where("`input_time` > $s1  and `input_time` <  $e1 and chanel = 6")->getCol();
			if(!empty($act_user)){
				$act = array_unique($act_user);
			}
			echo '从'.date('y-m-d H:i:s',$s).'到'.date('y-m-d H:i:s',$e).'登陆的用户个数为：'.sizeof($act).'</br></br>';
			//发帖
			$p_user = $this->db->model('purchase')->select('user_id')->where("`input_time` > $s  and `input_time` <  $e and `sync` = 6")->getCol();
			if(!empty($p_user)){
				$p_act = array_unique($p_user);
			}
			echo '从'.date('y-m-d H:i:s',$s).'到'.date('y-m-d H:i:s',$e).'发帖的用户个数为：'.sizeof($p_act).'</br></br>';
			//发帖条数
			$p_u = $this->db->model('purchase')->select('id')->where("`input_time` > $s  and `input_time` <  $e and `sync` = 6")->getCol();
			echo '从'.date('y-m-d H:i:s',$s).'到'.date('y-m-d H:i:s',$e).'发帖的总数为：'.sizeof($p_u).'</br></br>';
			//有回复的
			$p_u_r = $this->db->select('p.id,w.user_id')->from('purchase p')->leftjoin('weixin_plasticzone w','w.pur_id = p.id')->where("p.`input_time` > $s  and p.`input_time` <  $e and p.`sync` = 6 and w.user_id > 0")->getCol();
			if(!empty($p_u_r)){
				$p_act = array_unique($p_u_r);
			}
			echo '从'.date('y-m-d H:i:s',$s).'到'.date('y-m-d H:i:s',$e).'被回复的总数：'.sizeof($p_act).'</br></br>';
			// 留存率
			$users = $this->db->model('customer_contact')->where("`input_time` > $s  and `input_time` <  $e and `chanel` = 6")->getCol();
			$users1 = $this->db->model('customer_contact')->where("`input_time` > $s1  and `input_time` <  $e1 and `chanel` = 6")->getCol();
			$jon = join($users,',');
			$acts = $this->db->model('log_login')->select('user_id')->where("`input_time` > $s1  and `input_time` <  $e1 and chanel = 6 and user_id in($jon)")->getCol();
			echo '从'.date('y-m-d H:i:s',$s).'到'.date('y-m-d H:i:s',$e).'注册人数：'.sizeof(array_unique($users)).'&nbsp;&nbsp;</br></br>从'.date('y-m-d H:i:s',$s1).'到'.date('y-m-d H:i:s',$e1).'注册人数：'.sizeof($users1).'&nbsp;&nbsp;</br></br>从'.date('y-m-d H:i:s',$s1).'到'.date('y-m-d H:i:s',$e1).'登录人数：'.sizeof(array_unique($act_user_next)).'&nbsp;&nbsp;</br></br>隔日留存人数：'.sizeof(array_unique($acts)).'留存率：'.sizeof(array_unique($acts))/sizeof($users);
		}
		//指定用户批量关注并发送短信
		public function focuse(){
			set_time_limit(0);
			// $users = $this->db->model('customer_contact')->where('chanel=6')->getAll();
			// // $this->getAttention(9825,116);
			// foreach ($users as $k => $v) {
			// 	$this->getAttention(28291,$v['user_id']);
			// }
		}
		public function getAttention($userid,$focused_id){
			$result = $this->db->model('weixin_fans')->select('id,status')->where("user_id=$userid and focused_id=$focused_id")->getRow();
			if(empty($result)){
				$_data = array(
					'user_id'=>$userid,
					'focused_id'=>$focused_id,
					'status'=>1,
					'input_time'=>CORE_TIME,
					);
				//发送短信
				$this->sendRemindMsg($userid,$focused_id,1);
				$this->db->model('weixin_fans')->add($_data);
			}
		}
		//发送提醒短信
		public function sendRemindMsg($userid,$focused_id,$from,$pub_time){
			$sender = $this->getZoneUserInfo($userid)['name'];
			$recevier = $this->getZoneUserInfo($focused_id)['name'];
			$mobile = $this->getZoneUserInfo($focused_id)['mobile'];
			$msg = '';
			switch ($from) {
				case 1:
					$msg=$recevier.'您好，您在塑料圈很受欢迎，'.$sender.'刚刚关注您了，您发布的供求信息他会去查看！【塑料圈通讯录 q.myplas.com 】';//关注短信
					break;
				case 2:
					$msg=$recevier.'您好，您在塑料圈'.date("H:i",$pub_time).'发布的供给/求购信息，有人在关注，给您留言了，请前往查看！【塑料圈通讯录 q.myplas.com 】';//回复短信
					break;
			}
			M('system:sysSMS')->send($focused_id,$mobile,$msg,8);//发送手机动态码
		}
		public function getZoneUserInfo($userid){
			return M('user:customerContact')->getListByUserid($userid);
		}
		// public function test1(){
		// 	$rand_arr = array(832,834,805,860,799,883);

		// 	set_time_limit(0);
		// 	$arr = $this->db->model('customer_contact')->select('user_id')->where("chanel=6 and customer_manager=0")->getAll();
		// 	foreach ($arr as $key => $value) {
		// 		$a = array_rand($rand_arr);
		// 		$cid = $rand_arr[$a];
		// 		$up_arr=array(
		// 			'customer_manager'=>$cid,
		// 			'update_time'=>CORE_TIME,
		// 			);
		// 		$this->db->model('customer_contact')->where("user_id= {$value['user_id']}")->update($up_arr);
		// 	}
		// }
	public function curl($mobile,$user_id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://apis.juhe.cn/mobile/get?phone='.$mobile.'&key=e22af4bf0da236749e0796c8730a926d');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		$ch = curl_init();
		$res = json_decode($output,true);
		// p($res);die();
		$err =array();
		if(!empty($res['result'])){
			$data['mobile_area'] = $res['result']['city'];
			$data['mobile_province'] = $res['result']['province'];
			M('public:common')->model('contact_info')->where("user_id=".$user_id)->update($data);
		}else{
			$err[$user_id] = $mobile;
		}
		if(!empty($err)){
			$_SESSION['mobileErr'] = $err;
		}
	}
	public function getMobileArea(){
		set_time_limit(0);
		$res = M('public:common')->model('customer_contact')->select("user_id,mobile")->getAll();
		foreach ($res as $key => $value) {
			if(!empty($value['mobile'])){
				$this->curl($value['mobile'],$value['user_id']);
			}
		}
	}
		

	public function product(){
		set_time_limit(0);
		// $products = $this->db->model('product')->select('id')->where("temp = 0")->getCol();
		// foreach ($products as $k => $v) {
		// 	$exit  = $this->db->model('sale_log')->where("p_id = $v")->getRow();
		// 	if(!empty($exit)){
		// 		
		// 	}
		// }
		$exit  = $this->db->model('sale_log')->select("p_id")->getCol();
		$s = join(',',array_unique($exit));
		 $this->db->model('product')->where("id  in ($s)")->update(array('temp'=>1));
	}
	public function svn(){
		
	}
}