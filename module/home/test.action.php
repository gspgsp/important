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
		public function update3(){
			set_time_limit(0);
			$info = $this->db->model('info')->getAll();
			foreach ($info as $k => $v) {
				$content = preg_replace('/height=\"[\d]*?\"/','',$v['content']);
				$this->db->model('info')->where("id = {$v['id']}")->update(array('content'=>saddslashes($content)));
			}
		}
		public function test2(){
			set_time_limit(0);
			for($i=0; $i<2000; $i++){
				$url="http://www.manmanbuy.com/list_{$i}.aspx";
				$opts = array(
				  	'http'=>array(
				    	'method'=>"GET",
				    	'header'=>"Accept-language: en\r\n" .
				            "Cookie: foo=bar\r\n".
				            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0\r\n"
				  )
				);
				$context = stream_context_create($opts);
				$html = iconv("GB2312//IGNORE","UTF-8", file_get_contents($url,false, $context));
				if($html){
					if(preg_match_all("/根据你的需求筛选([\s\S]+?)进行比价/", $html,$matches)){
						$temp = array();
						unset($matches[0]);
						$cate_name = trim($matches[1][0]);
						$items = $this->db->model('cate')->where("`cate_name`= '$cate_name'")->getRow();
						if(empty($items)){
							$temp['cate_name'] = $cate_name;
							$temp['url'] = saddslashes($url);
							$temp['cate_id'] = $i;
							$temp['input_time'] = CORE_TIME;
							$this->db->model('cate')->add($temp);
						}
					}
				}
			}
		}
		public function test1(){
			$str = '<?xml version="1.0" encoding="UTF-8"?><response><error>-9001</error><message></message></response>';
			$s = xml2array($str);
			p($s);die;
		}
		//采集原油和布伦特价格
		public function get_plastic_price($id = 1){
			set_time_limit(0);
			$urlarr = array(
				'1'=>'monitor-33-0.html',     //HDPE
				'2'=>'monitor-9-0.html',       //LDPE
				'3'=>'monitor-1-0.html',       //LLDPE
				'4'=>'monitor-894-0.html',   //均聚PP粒
				'5'=>'monitor-898-0.html',   //共聚PP粒
				'6'=>'monitor-45-0.html',     //PVC	
			);
			$url="http://www.sci99.com/$urlarr[$id]";
			$opts = array(
				'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
				"Cookie: foo=bar\r\n".
				"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0\r\n"
				)
			);
			$context = stream_context_create($opts);
			$html = file_get_contents($url,false, $context);
			if($html){
				if(preg_match_all("/<tr>[\s]+?<td width=\"140\">([\S\s]+?)<\/td>[\s]+?<td width=\"140\">([\S\s]+?)<\/td>[\s]+?<td width=\"140\".*?>([\S\s]+?)<\/td>[\s]+?<td width=\"140\".*?>([\S\s]*?)<\/td>[\s]+?<td>([\S\s]+?)<\/td>/", $html, $matches)){
						if(!empty($matches[1])){
							$now =strtotime(date('Y-m-d'));
							foreach ($matches[1] as $k => $v) {
								$is_rof = $matches[3][$k]>0 ? 3 : ($matches[3][$k]==0 ? 2 : 1);
								$data = array(
									'input_time'=>CORE_TIME,
									'addtime'=>strtotime($v),
									'price'=>trim($matches[2][$k]),
									'rof'=>trim($matches[3][$k]),
									'avgprice'=>trim($matches[5][$k]),
									'is_rof'=>$is_rof,
									'cid'=>$id,
								);
								$items = $this->db->model('market')->where("`addtime`= $now")->getRow();
								if(empty($items)){
									$this->db->model('market')->add($data);
								}else{
									$updata=array(
										'update_time'=>CORE_TIME,
										'price'=>trim($matches[2][$k]),
										'rof'=>trim($matches[3][$k]),
										'avgprice'=>trim($matches[5][$k]),
										'is_rof'=>$is_rof,
										);
									$this->db->model('market')->where("`addtime`= $now and `cid` = $id")->update($updata);
								}
							}
						}					
						
					}
				}
			
		}
}