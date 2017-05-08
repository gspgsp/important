<?php
/**
 * 前台报价，对指定牌号点击发送短信按钮，然后将数据写到p2p_offers_cron中间表，
 * 该定时脚本是巡查该表下是否有状态为0的记录
 * 如果有，则用脚本匹配该牌号，发送短信
 * 该巡查任务，每分钟跑一次
 * Create: 叶中宝17.05.05
 */
 
require_once 'config.php';

$cron = new cronsendOffersMsg;
$cron->start();

class cronsendOffersMsg{
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
		$this->logfile=CACHE_PATH.'log/cron/sendOffersMsg.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		set_time_limit(0);
		$this->Send();
	}
	
	/**
	 * 发送短信
	 * @access private 
	 */
	private function Send(){
		$res = $this->db->model('offers_cron')->where('status = 0')->getAll();
		if(!$res) return;
		foreach ($res as $key => $value) {
			//先将状态改为正在发送
			$this->db->model('offers_cron')->where('id='.$value['id'])->update(array('status'=>1));
		}
		$count = $this->db->model('customer')->getOne("SELECT COUNT(c_id) FROM `p2p_customer`
		WHERE customer_manager>0  AND `STATUS` <> 9 AND (need_product <> '' OR need_product_adm <> '')");
		$this->nlimit=1000; //每次查询1000条
		$nums = ceil($count/$this->nlimit);
		foreach ($res as $key => $value) {
			for($i=0;$i<$nums;$i++){
				$this->getCustomerContact($i,$value);
				sleep(1);
			}
			$this->db->model('offers_cron')->where('id='.$value['id'])->update(array('status'=>2));
		}
	}
	public function getCustomerContact($i,$offers_info){
		// p($offers_info);die;
		$res = $this->db->model('customer')->select('c_id,c_name,need_product_adm,need_product')->where("customer_manager>0 and status <> 9 and msg = 2 and (need_product <> '' OR need_product_adm <> '')")->limit($i*$this->nlimit.",".$this->nlimit)->getAll();
		// p($res);
		// showtrace();
			foreach ($res as $key => $value) {
				$need_product_temp =array();
				$need_product_adm_temp =array();
				$need_product_adm_temp=split(',',$value['need_product_adm']);
				if(strpos($value['need_product'], ',')){
					$need_product_temp=array_merge($need_product_temp, split(',',$value['need_product']));
				}elseif( strpos(trim($value['need_product']), ' ')){
					$need_product_temp=array_merge($need_product_temp,split(' ',$value['need_product']));
				}else{
					array_push($need_product_temp, $value['need_product']);
				}
				//一个公司所需牌号
				$need_product=array_filter(array_values(array_unique(array_merge($need_product_temp,$need_product_adm_temp))));
				// p($need_product);
				if(in_array($offers_info['grade'],$need_product)){
					$this->addMsgLog($value['c_id'],$offers_info);
				}
				unset($need_product);
		}
	}
	public function addMsgLog($c_id,$offers_info){
		$res = $this->db->model('customer')->getAll("SELECT c1.`user_id`,c1.`name` as contact_name,c1.`c_id`,c1.`mobile` AS contact_mobile,customer_manager,adm.`name`,adm.`mobile` FROM `p2p_customer_contact` AS c1
			LEFT JOIN p2p_admin AS adm ON c1.`customer_manager` = adm.`admin_id`
			LEFT JOIN `p2p_adm_role_user` AS `user` ON `user`.`user_id` = c1.`customer_manager`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = `user`.`role_id`
			WHERE c1.`status` = 1 AND c1.`customer_manager` > 0 AND role.`pid` = 22 AND adm.`status` = 1 AND adm.`mobile` <> '' and c1.`name` <> '' AND c1.`mobile` <> '' and c1.`c_id` = ".$c_id);
		// showtrace();
		if(!$res){
			return;
		}
		$date = date("m月d日",time());
		foreach ($res as $key => $value) {
			if(is_mobile($value['contact_mobile'])){
				$msg = sprintf(L('offers_sms.offers'),$offers_info['grade'],$offers_info['sale_price'],$date,$value['name'],$value['mobile']);
	    		M('system:sysSMS')->send($value['user_id'],$value['contact_mobile'],$msg,12,0,$offers_info['offers_id']);
	    	}
		}
	}
}