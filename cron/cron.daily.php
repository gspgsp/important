<?php
/**
 * 每天定点统计业务员的业绩完成状况（12点，15点，17点）
 * 12点统计昨天的数据，17点统计当日15点前的数据，17点统计当日17点前的数据
 */
require_once 'config.php';

$cron = new cronDaily();
$cron->start();
class cronDaily{
	private $db=NULL; //数据库资源
	private $otime=0; //当前时间
	private $logfile=''; //日志文件
	/**
	 * 构造函数
	 * @access public
	 */
	public function __construct() {
        set_time_limit(0);
		$this->otime=time();
		$this->logfile=CACHE_PATH.'log/cron/daily.log'; //日志文件
		$this->db=M('public:common');
	}
	public function curl($to_uid,$data){
		// 指明给谁推送，为空表示向所有在线用户推送
		// $to_uid = '784';
		// 推送的url地址，上线时改成自己的服务器地址
		$push_api_url = "http://www.myplas.com:2121/";
		$post_data = array(
		   'type' => 'pc',
		   'content' => $data,
		   'to' => $to_uid, 
		);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		// var_export($return);
	}
	public function start(){
		$t1 = microtime(true);
		//15点推送消息（业务员、组长，主管），拉数据
		//17点推送消息（业务员、组长，主管），拉数据
		//其他整点拉数据（12，13，14，16，18，19-定时跑）
		$now = date('H',time());
		$start = strtotime(date('Y-m-d',time()));
		$end = time();
		if($now == 15){
			$this->get_calld_saled_buyd($start,$end,$now);//推送业务员
			$this->pushHeadmanMsg();//推送组长
			$this->pushLeaderMsg();//推送主管
		}elseif($now == 17){
			$this->get_calld_saled_buyd($start,$end,$now);//推送业务员
			$this->pushHeadmanMsg();//推送组长
			$this->pushLeaderMsg();//推送主管
		}else{
			$this->get_calld_saled_buyd($start,$end,$now);//拉数据
		}
		$t2 = microtime(true);
		echo round($t2-$t1,3);
	}

	public function get_calld_saled_buyd($start,$end,$now){
		$res= M('product:order')->getAllCustomerManagerTodayNum($start,$end);
		// showtrace();
		p($res);die;
		foreach ($res as $key => $value) {
			if(($value['sale']+$value['buy']) == '0' && ($value['call_num'] < '40' or $value['call_time'] < '2400')){
				$msg_res = array('admin_id'=>$value['customer_manager'],'msg'=>'截至此时，您当日完成吨数为0吨,当日电话数量完成'.$value['call_num'].'个,当日通话时长完成'.ceil($value['call_time']/60).'分钟,当日目标还未完成');
				if($now == 15 || $now == 17){
		        	$this->curl($msg_res['admin_id'],$msg_res['msg']);
		        	// $this->curl('1',$msg_res['msg']);die;//测试
				}
			}
			$admin_name = M('rbac:adm')->getUserByCol($value['customer_manager']);
			$_alert=array(
				'customer_manager'=>$value['customer_manager'],
				'call_num'=>$value['call_num'],
				'sale'=>$value['sale'],
				'buy'=>$value['buy'],
				'team_id'=>$value['team_id'],
				'admin_name'=>$admin_name,
				'input_time'=>time(),
			);
			$where = 'customer_manager='.$value['customer_manager'].' and input_time > '.$start.' and input_time <'.$end;
			$select_data = $this->db->model('daily_done')->where($where)->getAll();

			if(empty($select_data)){
				$this->db->model('daily_done')->add($_alert);
			}else{
				$this->db->model('daily_done')->where($where)->update($_alert);
			}
		}
  }
  /**
   * 给各个战队的组长推送消息
   * @Author   yezhongbao
   * @DateTime 2016-11-10T08:47:56+0800
   * @return   [type]                   [description]
   */
  public function pushHeadmanMsg(){
  		$array = array('张玉超'=>'774',
						'孙朝晖'=>'775',
						'范小勇'=>'847',
						'王凯晨'=>'734',
						'刘京'=>'737',
						'杨杰'=>'735',
						'季雯琼'=>'730',
						'沈辉'=>'772',
						'李红颖'=>'912',
						'李俊松'=>'911',
						'张玉超2'=>'946',
						'王春华'=>'955',
						'徐胜'=>'784',
						);
  		foreach ($array as $key => $value) {
        	$this->curl($value,'请及时查看业务员每日考核数据');
        	// $this->curl('1','请及时查看业务员每日考核数据'); die;//测试
  		}
  }
    /**
   * 给销售主管推送消息
   * @Author   yezhongbao
   * @DateTime 2016-11-10T08:47:56+0800
   * @return   [type]                   [description]
   */
  public function pushLeaderMsg(){
  	$arr = array('饶伟平'=>'10',
					'赵飞'=>'11',
					'李总'=>'726',
					);
  		foreach ($arr as $key => $value) {
        	$this->curl($value,'请及时查看业务员每日考核数据');
        	// $this->curl('1','请及时查看业务员每日考核数据');die; //测试
  		}
  }
}