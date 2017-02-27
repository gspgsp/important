<?php
/**
 * 每天凌晨的计划任务
 * Create: xqj@2016-09-23
 */
#59 23 * * * * /usr/bin/php
 
require_once 'config.php';

$cron = new cronResourcelib;
$cron->start();

/**
 * 每分钟需处理计划任务主类
 */
class cronResourcelib{
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
		$this->ranking();//计算塑料圈用户的排名
	}
	/**
	 * 处理资源库手机号和QQ号同步
	 * @Author   xqj
	 * @DateTime 2016-09-23
	 */
	private function ranking(){
		set_time_limit(0);
		$sql="SELECT id,user_qq,content FROM  p2p_resourcelib WHERE (user_qq IS NOT NULL AND RTRIM(LTRIM(user_qq))<>'') AND content  REGEXP \"[1][35678][0-9]{9}\" AND (RTRIM(LTRIM(mobile))='' OR  mobile IS NULL) LIMIT 0,10000";
		$list =$this->db->getAll($sql);
		$mobiles ='';
		//         p($list);
		foreach ($list as $k => $v) {
		    $exists = $this->db->model('resourcelib')->select('COUNT(0)')->where("id = {$v['id']}")->getOne();
		    if($exists<=0){//不存在 则插入记录
		    }else{//存在更新记录
		        preg_match("/1[34578]{1}\d{9}/", $v['content'], $mobiles);
		        $this->db->model('resourcelib')->where("id = {$v['id']}")->update(array('mobile'=>$mobiles[0],));
		    }
		}
	}
}