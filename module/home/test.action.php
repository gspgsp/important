<?php
class testAction extends homeBaseAction{
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
		$url="http://oil.chem99.com/channel/crudeoil/";
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
			if(preg_match_all("/style=\"color:#008000;font-size:12px;\">([\s\S]+?)↓([\s\S]+?)<\/span>/", $html, $matches)){
				$temp = array();
				unset($matches[1][2]);
				foreach ($matches[1] as $k=>$v) {
					$temp[$k]['ups_downs'] = trim($matches[2][$k]); //涨跌幅
					$temp[$k]['input_time'] =  time();
					$temp[$k]['type'] =  $k==0 ? 0 : 1;
					$temp[$k]['price'] = $v;
					$items = $this->db->model('oil_price')->where("`price`= $v and `ups_downs` = {$matches[2][$k]}")->getRow();
					if(empty($items)){
						$this->db->model('oil_price')->add($temp[$k]);
					}
				}
			}
		}
	}

	public function test(){
		
	}
}