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
		$arr = 'a:19:{s:12:"rotatedAngle";N;s:4:"name";a:1:{i:0;s:9:"兰家川";}s:5:"title";a:1:{i:0;s:21:"华东区咨询总监";}s:3:"tel";a:1:{i:0;s:11:"02164752227";}s:6:"mobile";a:1:{i:0;s:11:"13816100037";}s:3:"fax";a:0:{}s:5:"email";a:1:{i:0;s:17:"lanjc@esa20do.com";}s:4:"comp";a:1:{i:0;s:30:"信息科技股份有限公司";}s:4:"dept";a:0:{}s:6:"degree";a:0:{}s:4:"addr";a:1:{i:0;s:47:"上海市衞[区宏润国际花园2号楼501室";}s:4:"post";a:0:{}s:4:"mbox";a:0:{}s:4:"htel";a:0:{}s:3:"web";a:2:{i:0;s:22:"http://www.esa2000.com";i:1;s:22:"http://www.esaysign.cn";}s:2:"im";a:0:{}s:8:"numOther";a:0:{}s:5:"other";a:1:{i:0;s:15:"北京支证通";}s:6:"extTel";a:0:{}}';
		$arr = unserialize($arr);
		p($arr);
	}
}