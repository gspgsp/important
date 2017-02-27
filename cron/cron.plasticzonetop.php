<?php
/**
 * 每小时执行一次的定时任务
 * Create: gsp@2016-11-11
 */
#59 * * * * * /usr/bin/php
require_once 'config.php';
$cron = new cronPlasticzoneTop();
$cron->start();

/**
 * 每分钟需处理计划任务主类
 */
class cronPlasticzoneTop{
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
		$this->topping();//计算用户积分置顶
	}
	/**
	 * 计算用户积分置顶
	 * @Author   gsp
	 * @DateTime 2016-11-11
	 */
	private function topping(){
		set_time_limit(0);
	    $data = $this->db->model('corn')->where("1 and (exe_time_e -  exe_time_s) > 0 and status_e = 0")->order('input_time desc')->getAll();
    	if($data){
    		$curtime = strtotime(date('Y-m-d H:i',$this->otime));
    		$daytime = strtotime(date('Y-m-d H:i:m',$this->otime));
    		foreach ($data as &$value) {
    			if($value['type']==0){
    				if($daytime >= $value['exe_time_s'] and $daytime <= $value['exe_time_e']){
    					$this->setConSatus($value['user_id'],1);
    					$this->setCronStarStatus($value['id'],$value['type']);
    				}elseif ($daytime > $value['exe_time_e']) {
    					$this->setConSatus($value['user_id'],0);
    					$this->setCronEndStatus($value['id'],$value['type']);
    				}
    			}elseif ($value['type']==1) {
    				if($curtime >= $value['exe_time_s'] and $curtime <= $value['exe_time_e']){
    					$this->setPurStatus($value['purchase'],1);
    					$this->setCronStarStatus($value['id'],$value['type']);
    				}elseif ($curtime > $value['exe_time_e']) {
    					$this->setPurStatus($value['purchase'],0);
    					$this->setCronEndStatus($value['id'],$value['type']);
    				}
    			}
    		}
    	}
	}
	private function setCronStarStatus($id,$topType){
		$data = array(
				'status_s'=>1,
				);
		if($topType == 0){
			$this->db->model('corn')->where("type = 0 and id = $id")->update($data);
		}elseif ($topType == 1) {
			$this->db->model('corn')->where("type = 1 and id = $id")->update($data);
		}
	}
	private function setCronEndStatus($id,$topType){
		$data = array(
				'status_e'=>1,
				);
		if($topType == 0){
			$this->db->model('corn')->where("type = 0 and id = $id")->update($data);
		}elseif ($topType == 1) {
			$this->db->model('corn')->where("type = 1 and id = $id")->update($data);
		}
	}
	private function setPurStatus($purId,$type){
		if($type == 1){
			$data = array(
			'top'=>1,
			'top_time'=>CORE_TIME,
			'update_time'=>CORE_TIME,
			);
			$this->db->model('purchase')->where("id=$purId")->update($data);
		}elseif ($type == 0) {
			$data = array(
			'top'=>0,
			'update_time'=>CORE_TIME,
			);
			$this->db->model('purchase')->where("id=$purId")->update($data);
		}
	}
	private function setConSatus($userid,$type){
		if($type == 1){
			$data = array(
			'top'=>1,
			'top_time'=>CORE_TIME,
			);
			$this->db->model('weixin_ranking')->where("user_id=$userid")->update($data);
		}elseif ($type == 0) {
			$data = array(
			'top'=>0,
			);
			$this->db->model('weixin_ranking')->where("user_id=$userid")->update($data);
		}
	}
}

