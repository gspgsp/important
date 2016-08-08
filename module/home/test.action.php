<?php
	class testAction extends homeBaseAction{
		protected $db;
		public function __init(){
			$this->db=M('public:common');
		}
		// public function test(){
		// 	set_time_limit(0);
		// 	$infos = $this->db->model('ouser')->getAll();
		// 	foreach ($infos as $k => $v) {
		// 		$aid =  $this->db->model('lib_region')->select('id')->where("name like '{$v['s_county']}'")->getOne();
		// 		$this->db->model('ouser')->where("user_id = {$v['user_id']}")->update(array('s_co'=>$aid));
		// 	}
		// }
		// public function update3(){
		// 	set_time_limit(0);
		// 	$info = $this->db->model('info')->getAll();
		// 	foreach ($info as $k => $v) {
		// 		$content = preg_replace('/height=\"[\d]*?\"/','',$v['content']);
		// 		$this->db->model('info')->where("id = {$v['id']}")->update(array('content'=>saddslashes($content)));
		// 	}
		// }
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
}