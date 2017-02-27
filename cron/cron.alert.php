<?php
/**
 * 每天下班前统计所有业务员当天业绩情况
 */
require_once 'config.php';

$cron = new cronAlert();
$cron->start();
class cronAlert{
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
		$this->logfile=CACHE_PATH.'log/cron/alert.log'; //日志文件
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
		// $this->curl('784','您有客户1:苏州俊俊塑胶原料有限公司,销项票金额192000.00未开，请及时跟进');
		// $this->curl('784','您有客户2:苏州俊俊塑胶原料有限公司,销项票金额192000.00未开，请及时跟进');
		// $this->curl('784','您有客户3:苏州俊俊塑胶原料有限公司,销项票金额192000.00未开，请及时跟进');
		$this->get_calld_saled_buyd();
		$this->get_invoice_status();
		$this->get_sale_owed();
		$this->get_offer_pur();
		// 
		$t2 = microtime(true);
		echo '耗时'.round($t2-$t1,3).'秒';
	}
	public function get_calld_saled_buyd(){
    	$date = strtotime(date('Y-m',time()));
    	$res = $this->db->model('report_user')->where('report_date = '.$date)->select('admin_id,day_call,day_calld,sales,saled,buys,buyd')->getAll();
    	foreach ($res as $key => $value) {
    	  	if ($value['day_calld'] < $value['day_call']) {
    	        $unfinished = $value['day_call']-$value['day_calld'];
    	        $call = array('admin_id'=>$value['admin_id'],'msg'=>'您今日电话量目标:'.$value['day_call'].'个,已完成:'.$value['day_calld'].'个,未完成:'.$unfinished.'个。');
    	        //消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['admin_id'],
					'content'=>$call['msg'],
					'type'=>1,//日电话量类型为1
					'input_time'=>time(),
					'is_read'=>0,
				);
				$this->db->model('alert_log')->add($_alert);
    	        $this->curl($call['admin_id'],$call['msg']);
    	    }
    	    $saled = round($value['saled']/10000,3);//销售额
    	    if ($saled < $value['sales']) {
    	        $unfinished = $value['sales']-$saled;
    	        $sale = array('admin_id'=>$value['admin_id'],'msg'=>'截至今日，您本月销售目标:'.$value['sales'].'万元,已完成:'.$saled.'万元,未完成:'.$unfinished.'万元。');
    	        //消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['admin_id'],
					'content'=>$sale['msg'],
					'type'=>2,//销售目标类型为2
					'input_time'=>time(),
					'is_read'=>0,
				);
				$this->db->model('alert_log')->add($_alert);
    	        $this->curl($sale['admin_id'],$sale['msg']);
    	    }
    	    $buyd = round($value['buyd']/10000,3);//采购额
    	    if ($buyd < $value['buys']) {
    	        $unfinished = $value['buys']-$buyd;
    	        $buy = array('admin_id'=>$value['admin_id'],'msg'=>'截至今日，您本月采购目标:'.$value['buys'].'万元,已完成:'.$buyd.'万元,未完成:'.$unfinished.'万元。');
    	         //消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['admin_id'],
					'content'=>$buy['msg'],
					'type'=>3,//采购目标类型为3
					'input_time'=>time(),
					'is_read'=>0,
				);
				$this->db->model('alert_log')->add($_alert);
    	        $this->curl($buy['admin_id'],$buy['msg']);
    	    }

    	}
  }
	public function get_invoice_status(){
		//销售完全没开票
		$sale_all_uninvoice = $this->db
					->model('order')
					->getAll('SELECT o.`customer_manager`,o.`o_id`,o.`order_sn`,o.`c_id`,SUM(o.total_price) AS price,cus.c_name FROM `p2p_order` AS o
							  JOIN `p2p_customer` AS cus ON cus.c_id = o.`c_id`
							  WHERE o.order_status = 2 AND o.transport_status = 2 AND o.order_type = 1 AND invoice_status = 1
							  GROUP BY o.customer_manager,o.c_id 
							  ORDER BY o.customer_manager DESC, o.c_id DESC'
						    );
		//销售部分开票
		$sale_part_uninvoice = $this->db
					->model('order')
					->getAll('SELECT o.`customer_manager`,o.`o_id`,o.`order_sn`,o.`c_id`,bill.`unbilling_price` AS price,cus.`c_name` FROM `p2p_order` AS o
						 	JOIN `p2p_billing` AS bill ON o.`o_id` = bill.`o_id`
							JOIN `p2p_customer` AS cus ON cus.c_id = o.`c_id`
							WHERE o.order_status = 2 AND o.transport_status = 2 AND o.order_type = 1 AND o.invoice_status = 2 AND bill.`invoice_status` =1
						    ');
		//采购完全没开票
		$pur_all_uninvoice = $this->db
					->model('order')
					->getAll('SELECT o.`customer_manager`,o.`o_id`,o.`order_sn`,o.`c_id`,SUM(o.total_price) AS price,cus.c_name FROM `p2p_order` AS o
							  JOIN `p2p_customer` AS cus ON cus.c_id = o.`c_id`
							  WHERE o.order_status = 2 AND o.transport_status = 2 AND o.order_type = 2 AND invoice_status = 1
							  GROUP BY o.customer_manager,o.c_id 
							  ORDER BY o.customer_manager DESC, o.c_id DESC'
						    );
		//采购部分开票
		$pur_part_uninvoice = $this->db
					->model('order')
					->getAll('SELECT o.`customer_manager`,o.`o_id`,o.`order_sn`,o.`c_id`,bill.`unbilling_price` AS price,cus.`c_name` FROM `p2p_order` AS o
						 	JOIN `p2p_billing` AS bill ON o.`o_id` = bill.`o_id`
							JOIN `p2p_customer` AS cus ON cus.c_id = o.`c_id`
							WHERE o.order_status = 2 AND o.transport_status = 2 AND o.order_type = 2 AND o.invoice_status = 2 AND bill.`invoice_status` =1
						    ');
		//处理采购开票
		foreach ($pur_part_uninvoice as $key => $value) {
			foreach ($pur_all_uninvoice as $k => $v) {
				if ($value['o_id'] == $v['o_id']) {
					$pur_all_uninvoice[$k]['price'] += $value['price'];
					unset($pur_part_uninvoice[$key]);
				}
			} 
		}
		foreach($pur_part_uninvoice as $k=>$v){
			array_push($pur_all_uninvoice,$v);
		}
		//处理销售开票
		foreach ($sale_part_uninvoice as $key => $value) {
			foreach ($sale_all_uninvoice as $k => $v) {
				if ($value['o_id'] == $v['o_id']) {
					$sale_all_uninvoice[$k]['price'] += $value['price'];
					unset($sale_part_uninvoice[$key]);
				}
			} 
		}
		foreach($sale_part_uninvoice as $k=>$v){
			array_push($sale_all_uninvoice,$v);
		}
		// p($pur_all_uninvoice);die;
		foreach ($sale_all_uninvoice as $key => $value) {
			$data = array('admin_id'=>$value['customer_manager'],'msg'=>'您有客户:'.$value['c_name'].',销项票金额'.$value['price'].'未开，请及时跟进');
	        	//消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['customer_manager'],
					'o_id'=>$value['o_id'],
					'order_sn'=>$value['order_sn'],
					'content'=>$data['msg'],
					'type'=>4,//销售未开票类型为4
					'input_time'=>time(),
					'is_read'=>0,
				);
			$this->db->model('alert_log')->add($_alert);
	        $this->curl($data['admin_id'],$data['msg']);

		}
		foreach ($pur_all_uninvoice as $key => $value) {
			$data = array('admin_id'=>$value['customer_manager'],'msg'=>'您有客户:'.$value['c_name'].',进项票金额'.$value['price'].'未开，请及时跟进');
				//消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['customer_manager'],
					'o_id'=>$value['o_id'],
					'order_sn'=>$value['order_sn'],
					'content'=>$data['msg'],
					'type'=>5,//采购未开票类型为5
					'input_time'=>time(),
					'is_read'=>0,
				);
			$this->db->model('alert_log')->add($_alert);
	        $this->curl($data['admin_id'],$data['msg']);
		}
	}
	public function get_sale_owed(){
		$sale_owed_data = $this->db
					->model('order')
					->getAll('SELECT o.`o_id`,o.`c_id`,o.`order_sn`,o.`customer_manager`,(o.`total_price`-SUM(IFNULL(coll.`collected_price`,0))) AS uncoll ,cus.`c_name`
						FROM `p2p_order` AS o
						LEFT JOIN `p2p_collection` AS coll ON o.`o_id` = coll.`o_id`
						JOIN `p2p_customer` AS cus ON o.`c_id` = cus.`c_id`
						WHERE o.`order_type` = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2 AND o.`collection_status` IN (1,2)
						GROUP BY o.`o_id`
					    ');
					// echo $this->db->getLastSql();die();
		foreach ($sale_owed_data as $key => $value) {
			if($value['uncoll'] > 0){
				$data = array('admin_id'=>$value['customer_manager'],'msg'=>'您有客户:'.$value['c_name'].',销项欠款金额：'.$value['uncoll'].'未收，请及时跟进');
					//消息弹窗日志
					$_alert=array(
						'customer_manager'=>$value['customer_manager'],
						'o_id'=>$value['o_id'],
						'order_sn'=>$value['order_sn'],
						'content'=>$data['msg'],
						'type'=>6,//销项欠款未收类型为6
						'input_time'=>time(),
						'is_read'=>0,
					);
				$this->db->model('alert_log')->add($_alert);
	        	$this->curl($data['admin_id'],$data['msg']);
			}
			
		}
	}
	//客户在网站发布报价或者求购了。提醒对应的交易员
	public function get_offer_pur(){
		$yesterday = strtotime(date('Y-m-d',time()-60*60*24));
		$today = strtotime(date('Y-m-d',time()));
		$where = " input_time > ".$yesterday." and input_time <".$today; 
		$offer_pur_data = $this->db
					->getAll('SELECT cus.`c_name`,pro.`model`,fac.`f_name`,pur.`number`,pur.`unit_price`,pur.`customer_manager`,pur.`type`,pur.`id`  
							FROM p2p_purchase AS pur
							JOIN p2p_customer AS cus ON cus.`c_id` = pur.`c_id`
							JOIN p2p_product AS pro ON pro.`id` = pur.`p_id`
							JOIN p2p_factory AS fac ON fac.`fid` = pro.`f_id`
							WHERE pur.`input_time` > '.$yesterday.' AND pur.`input_time` < '.$today);
					// p($offer_pur_data);die;
		foreach ($offer_pur_data as $key => $value) {
			if($value['type'] == 1 ){
				$offer_pur_data[$key]['type_text'] = '求购';
			}elseif($value['type'] == 2){
				$offer_pur_data[$key]['type_text'] = '报价';
			}
			$data = array('admin_id'=>$value['customer_manager'],'msg'=>'您有客户:'.$value['c_name'].'已发布'.$offer_pur_data[$key]['type_text'].',牌号：'.$value['model'].',厂家：'.$value['f_name'].',吨数：'.$value['number'].',单价：'.$value['unit_price'].'，请及时跟进!');
			// p($data);die;
			//消息弹窗日志
				$_alert=array(
					'customer_manager'=>$value['customer_manager'],
					'o_id'=>$value['id'],//(type=7时，此id为求购/报价的自增id)
					'content'=>$data['msg'],
					'type'=>7,//报价/求购信息类型为7
					'input_time'=>time(),
					'is_read'=>0,
					'purchase_type'=>$value['type'],
				);
			$this->db->model('alert_log')->add($_alert);
	        $this->curl($data['admin_id'],$data['msg']);
		}		
	}
}