<?php
class testinfoAction extends homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	/**
	 * 首页基金洞察采集
	 */
	public function init(){
		set_time_limit(0);
		$urlarr = array(
			'0'=>'monitor-33-0.html',     //HDPE
			'1'=>'monitor-9-0.html',       //LDPE
			'2'=>'monitor-1-0.html',       //LLDPE
			'3'=>'monitor-894-0.html',   //均聚PP粒
			'4'=>'monitor-898-0.html',   //共聚PP粒
			'5'=>'monitor-45-0.html',     //PVC	
		);
		for ($i=0; $i <=count($urlarr); $i++) { 
			$url="http://www.sci99.com/".$urlarr[$i];
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
				if(preg_match_all("/<td width=\"140\">([\S\s]+?)<\/td>/", $html, $matches)){
					$match[$i+1]=array_splice($matches[0],0,2);
				}
			}
		}
		foreach ($match as $k => $v) {
			$temp[$k]['addtime']=str_replace("t", '', $v[0]);
			$temp[$k]['price']=trim($v[1]);
		}
		p($temp);die;
	
				
				// foreach ($matches[0] as $k=>$v) {
				// 	$temp[$k]['ups_downs'] = trim($matches[2][$k]); //涨跌幅
				// 	$temp[$k]['input_time'] =  time();
				// 	$temp[$k]['type'] =  $k==0 ? 0 : 1;
				// 	$temp[$k]['price'] = $v;
				// 	$items = $this->db->model('oil_price')->where("`price`= $v and `ups_downs` = {$matches[2][$k]}")->getRow();
				// 	if(empty($items)){
				// 		$this->db->model('oil_price')->add($temp[$k]);
				// 	}
				// }
			
		
	}

	public function test(){
		
	}
}