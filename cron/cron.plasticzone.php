<?php
/**
 * 每天凌晨的计划任务
 * Create: xqj@2016-09-23
 */
#59 23 * * * * /usr/bin/php
 
require_once 'config.php';

$cron = new cronPlasticzone;
$cron->start();

/**
 * 每分钟需处理计划任务主类
 */
class cronPlasticzone{
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
	 * 计算塑料圈用户的排名
	 * @Author   xqj
	 * @DateTime 2016-09-23
	 */
	private function ranking(){
		set_time_limit(0);
	    $sql="SELECT rank.*,@rownum:=@rownum+1 AS rownum FROM
        (SELECT aa.user_id,COUNT(b.id)*0.5 + aa.pm AS  pm FROM 
        (
        SELECT a.user_id, (SELECT IFNULL(COUNT(user_id),0)*0.3 FROM p2p_purchase WHERE user_id=a.user_id AND TYPE=1 AND sync=6) + (SELECT IFNULL(COUNT(user_id),0)*0.2 FROM p2p_purchase WHERE user_id=a.user_id AND TYPE=2 AND sync=6) pm
        FROM p2p_customer_contact  a
        LEFT JOIN p2p_purchase c ON c.user_id=a.user_id
        WHERE chanel=6 AND a.c_id NOT IN (SELECT c_id  FROM p2p_customer WHERE c_name LIKE '%中晨%')
        GROUP BY a.user_id
        UNION ALL 
        SELECT a.user_id, 0 pm
        FROM p2p_customer_contact  a
        LEFT JOIN p2p_purchase c ON c.user_id=a.user_id
        WHERE chanel=6 AND a.c_id IN (SELECT c_id  FROM p2p_customer WHERE c_name LIKE '%中晨%')
        GROUP BY a.user_id
        )aa
        LEFT JOIN p2p_weixin_fans b ON b.focused_id=aa.user_id AND b.status=1
        GROUP BY aa.user_id
        ORDER BY  pm DESC) rank, (SELECT @rownum:=0) r";
        $list =$this->db->getAll($sql);
        foreach ($list as $k => $v) {
            $exists = $this->db->model('weixin_ranking')->select('COUNT(0)')->where("user_id = {$v['user_id']}")->getOne();
            if($exists<=0){//不存在 则插入记录
                $this->db->model('weixin_ranking')->add($v);
            }else{//存在更新记录
                $this->db->model('weixin_ranking')->where("user_id = {$v['user_id']}")->update(array('pm'=>$v['pm'],'rownum'=>$v['rownum'],));
            }
        }
	}
}