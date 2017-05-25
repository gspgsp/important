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
		$this->dbProcess(); //处理数据库日志
		$this->get_oil_price();//获取原油价格
		$this->profit();//计算每一笔的销售订单的利润
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
					$items = $this->db->model('oil_price')->where("`price`= {$temp['price']} and `type` = {$temp['type']}")->getRow();
					if(empty($items)){
						$this->db->model('oil_price')->add($temp);
					}
				}
				M('operator:oilPrice')->delCache();
			}
		}
	}
	/**
	 * 处理数据库数据
	 */
	private function dbProcess(){
		$time_stamp = strtotime('last day 23:59:59');
		//把log_sms前一日的数据移到log_sms_history
		$this->db->query('insert into '.$this->db->table('log_sms_history').' select * from '.$this->db->table('log_sms').' where status > 0 AND input_time < '.$time_stamp);
		$this->db->query('delete from '.$this->db->table('log_sms').' where status > 0 AND input_time < '.$time_stamp);

               array('input_time'=>time(),'user_id'=>$id,'ending'=>$ending,'weight'=>$weight,'price'=>$province,'status'=>$status,'`starting`'=>$starting)) ;
$this->db->query('insert into '.$this->db->table('ship_collect')." (`input_time`,`user_id`,`ending`,`weight`,`price`,`status`,`starting`) VALUES (".time().", $id, $ending, $weight, $province, $status, $starting)")
	}
	/**
	 * 计算每笔销售订单明细的利润
	 * @Author   cuiyinming
	 * @DateTime 2016-09-19T11:09:24+0800
	 * @return   [type]                   [description]
	 */
	private function profit(){
		set_time_limit(0);
		$time_stamp = strtotime("-10day 23:59:59");
		$list = $this->db->model('sale_log')->where("input_time > $time_stamp and inlog_id > 0 and `profit` = 0 ")->getAll();
		foreach ($list as $k => $v) {
			//根据sale_log查询关联inlog的采购价格
			$p_price = $this->db->model('in_log')->select('unit_price')->where("id = {$v['inlog_id']}")->getOne();
			// 计算利润
			if($p_price>0){
				$this->db->model('sale_log')->wherePk($v['id'])->update(array('profit'=>($v['unit_price']-$p_price)*$v['number'],));
			}
		}
	}
}