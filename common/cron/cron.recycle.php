<?php
/**
 * 客户从新回收
 */
require_once 'config.php';
$cron = new cronRecycle();
$cron->start();
class cronRecycle{
	private $db=NULL; //数据库资源
	private $otime=0; //当前时间
	private $logfile=''; //日志文件
	/**
	 * 构造函数
	 * @access public
	 */
	public function __construct(){
		$this->otime=time();
		$this->logfile=CACHE_PATH.'log/cron/Recycle_daily.log'; //日志文件
		$this->db=M('public:common');
	}
	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->dbProcess(); //处理用户回收问题
	}
	/**
	 * 处理用户数据主要程序
	 */
	public function dbProcess(){
		set_time_limit(0);
		//查询配置信息
		$limit =json_decode($this->db->model('setting')->select("value")->where("`code` = 'limit'")->getOne(),ture);
		$last_time = 86400*intval($limit['limit']['back']);
		$cache =cache::startMemcache();
		if($limit['limit']['recycle'] > 0){
			//查所有的交易员
			$rules = $this->db->model('dismiss_rule')->getAll();
			if(!empty($rules)){
				foreach ($rules as $rule) {
					//查询符合规则的交易员id
					if($rule['id']>0){
						$cus = $this->db->model('dismiss_rule_admin')->select('admin_id')->where("`rule_id` = {$rule['id']}")->getCol();
						if(!empty($cus)){
							$uncooper = time()-intval($rule['private_uncooperation_days'])*86400; //私海客户无合作强开天数
							$follow =  time()-intval($rule['private_unfollow_days'])*86400;//私海客户无跟进强开
							$cooper =  time()-intval($rule['cooperation_days'])*86400;//已合作强开
							foreach ($cus as $cu) {
								//获取对应交易员的所有客户强开（需要满足的条件 1本交易员 2 时间超时 3为处理过 4 有跟踪记录）
								$customers = $this->db->model('customer')->select("`c_id`,`last_follow`,`last_sale`,`last_no_sale`,`is_sale`,`is_pur`")->where("`customer_manager` = $cu AND ((`last_follow` < $follow and `last_follow` > 1 AND `is_pur` = 0 AND `is_sale` =0 ) or (`last_sale` < $cooper  and `last_sale` > 1 AND `is_sale` = 1) or  (`last_no_sale` < $uncooper and `last_no_sale` > 1 AND `is_pur` = 0 AND `is_sale` = 0))")->getAll();
								//print_r($customers);
								//print_r($this->db->getLastSql());
								// print_r($rule['private_uncooperation_days']);
								// print_r($rule['private_unfollow_days']);
								// print_r($rule['cooperation_days']);
								if($customers){
									foreach ($customers as $cinfo) {
										//处理跟踪问题
										if(($cinfo['last_follow'] < $follow || $cinfo['last_no_sale'] < $uncooper)  && $cinfo['is_sale'] != 1 && $cinfo['is_pur'] != 1){
											#print_r('bbbbbbbbbbbbbbbbb'.$cinfo['c_id']);
											//更新超时人员的交易员为0
											if($cinfo['last_follow'] < $follow){
												$this->db->model('customer')->where("`c_id` = {$cinfo['c_id']}")->update(array('last_follow'=>1,'customer_manager'=>0));
												//新增客户流转记录日志----S--	//打log
												$remarks = "对客户操作：用户跟踪时间超过强开规则，系统自动处理至公海";
											}
											if($cinfo['last_no_sale'] < $uncooper){
												$this->db->model('customer')->where("`c_id` = {$cinfo['c_id']}")->update(array('last_no_sale'=>1,'customer_manager'=>0));
												//新增客户流转记录日志----S--	//打log
												$remarks = "对客户操作：私海客户未合作超时，系统自动处理至公海";
											}
											M('user:customerLog')->addLog($cinfo['c_id'],'check','私海客户','公海客户',1,$remarks);
											//新增客户流转记录日志----E
											// 处理3天以内老交易员不能认领自己之前的客户
											$_key = $cinfo['c_id'].'_'.$cu;
											$cache->set($_key,1,$last_time);
										}
										//处理合作问题
										if($cinfo['last_sale'] < $cooper && $cinfo['is_sale'] == 1){
											#print_r('aaaaaaaaaaaaaaaa'.$cinfo['c_id']);
											//更新超时人员的交易员为0
											$this->db->model('customer')->where("`c_id` = {$cinfo['c_id']}")->update(array('last_sale'=>1,'customer_manager'=>0));
											//新增客户流转记录日志----S--	//打log
											$remarks = "对客户操作：已合作客户超过规则没有订单，根据强开规则，系统自动处理至公海";
											M('user:customerLog')->addLog($cinfo['c_id'],'check','已合作私海客户','公海客户',1,$remarks);
											//新增客户流转记录日志----E
											// 处理3天以内老交易员不能认领自己之前的客户
											$_key = $cinfo['c_id'].'_'.$cu;
											$cache->set($_key,1,$last_time);
										}
										//处理私海
										//if($cinfo['last_no_sale'] < $uncooper && $cinfo['is_sale'] != 1 && $cinfo['is_pur'] != 1){
										//	#print_r('cccccccccccccccccccccccccc'.$cinfo['c_id']);
										//	//更新超时人员的交易员为0
										//	$this->db->model('customer')->where("`c_id` = {$cinfo['c_id']}")->update(array('last_no_sale'=>1,'customer_manager'=>0));
											//新增客户流转记录日志----S--	//打log
										//	$remarks = "对客户操作：未合作客户超过强开规则，系统自动处理至公海";
										//	M('user:customerLog')->addLog($cinfo['c_id'],'check','未合作私海客户','公海客户',1,$remarks);
											//新增客户流转记录日志----E
											// 处理3天以内老交易员不能认领自己之前的客户
										//	$_key = $cinfo['c_id'].'_'.$cu;
										//	$cache->set($_key,1,$last_time);
										//}
									}
								}
								
							}
						}
					}
				}
			}
		}
	}
}
