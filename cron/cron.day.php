<?php
/**
 * 每天凌晨的计划任务
 * Create: Andy@2013-02-16
 */
#59 23 * * * * /usr/bin/php
 
require_once 'config.php';

$cron = new cronDay;
$cron->start();

/**
 * 每分钟需处理计划任务主类
 */
class cronDay{
	private $db=NULL; //数据库资源
	private $otime=0; //当前时间
	private $logfile=''; //日志文件
	private $nlimit=10; //每次处理个数

	/**
	 * 构造函数
	 * @access public 
	 */
	public function __construct() {
		$this->otime=time();
		$this->logfile=CACHE_PATH.'log/cron/day.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		for($i=1;$i<7;$i++){
			$this->get_plastic_price($i);
		}
		$this->get_oil_price();
	}
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
			// p($html);
			if(preg_match_all("/<tr>[\s]+?<td width=\"140\">([\S\s]+?)<\/td>[\s]+?<td width=\"140\">([\S\s]+?)<\/td>[\s]+?<td width=\"140\".*?>([\S\s]+?)<\/td>[\s]+?<td width=\"140\".*?>([\S\s]*?)<\/td>[\s]+?<td>([\S\s]+?)<\/td>/", $html, $matches)){
					if(!empty($matches[1])){
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
							$items = $this->db->model('market')->where("`price`= {$data['price']} and `avgprice` = {$data['avgprice']}")->getRow();
							if(empty($items)){
								$this->db->model('market')->add($data);
							}
						}
					}					
					
				}
			}
		
	}
	//采集原油和布伦特价格
	public function get_oil_price(){
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
			if(preg_match_all("/<span id=\"Menu1_lb_wti\".*?>([\s\S]+?)<\/span>.*?<span id=\"Menu1_lb_brent\".*?>([\s\S]+?)<\/span>/", $html, $matches)){
				unset($matches[0]);
				foreach ($matches as $k=>$v) {
					$temp = array();
					$temp['type'] =  $k==1 ? 0 : 1;
					$temp['price'] = substr($v[0],0,5);
					$temp['input_time'] = strtotime(date("Y/m/d"))-86400;
					$temp['ups_downs'] = substr($v[0],8);
					$items = $this->db->model('oil_price')->where("`price`= {$temp['price']} and `input_time` = {$temp['input_time']} and `type` = {$temp['type']}")->getRow();
					if(empty($items)){
						$this->db->model('oil_price')->add($temp);
					}else{
						$temp['update_time'] = CORE_TIME;
						$this->db->model('oil_price')->where("`input_time` = {$temp['input_time']} and `type` = {$temp['type']}")->update($temp);
					}
				}
				M('operator:oilPrice')->delCache();
			}
		}
	}
}