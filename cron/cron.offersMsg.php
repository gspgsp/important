<?php
/**
 * 报价推送短信，匹配每日系统录入的牌号信息，然后给对应的联系人发短信
 * Create: 叶中宝17.05.03
 */
 
require_once 'config.php';

$cron = new cronOffersMsg;
$cron->start();

/**
 * 发送短信/邮件
 */
class cronOffersMsg{
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
		$this->logfile=CACHE_PATH.'log/cron/offersmsg.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		set_time_limit(0);
		$count = $this->db->model('customer')->getOne("SELECT COUNT(c_id) FROM `p2p_customer`
			WHERE customer_manager>0  AND `STATUS` <> 9 AND (need_product <> '' OR need_product_adm <> '')");
		$this->nlimit=1000; //每次发送1000条
		$nums = ceil($count/$this->nlimit);
		for($i=0;$i<$nums;$i++){
			$this->sendOffersMsg($i);
				sleep(1);
		}
	}
	
	/**
	 * 报价推送
	 * @Author   yezhongbao
	 * @DateTime 2017-05-03T17:44:58+0800
	 * @return   [type]                   [description]
	 */
	public function sendOffersMsg($i){
		$today_time = strtotime('today');
		//发布报价的信息，去重，相同报价取最后一条
		$product = $this->db->model('offers_msg')->getAll('SELECT a.`id`,a.`grade`,a.`sale_price`,a.`china_area` FROM p2p_offers_msg AS a,(SELECT MAX(id) AS id,grade FROM p2p_offers_msg GROUP BY grade) b
			WHERE a.`id`=b.`id` AND a.`grade`=b.`grade` AND a.`status` = 2 and a.`input_time` > '.$today_time);
		// showtrace();
		//取出报价中的牌号这一列
		if(empty($product)) return;
		// p($product);
		foreach ($product as $key1 => $value1) {
			switch ($value1['china_area']) {
				case '华东':
					$product_arr_huadong[] = trim($value1['grade']);
					$id_arr_huadong[] = $value1['id'];
					break;
				case '华北':
					$product_arr_huabei[] = trim($value1['grade']);
					$id_arr_huabei[] = $value1['id'];
					break;
				case '华南':
					$product_arr_huanan[] = trim($value1['grade']);
					$id_arr_huanan[] = $value1['id'];
					break;
				default:
					$product_arr_qita[] = trim($value1['grade']);
					$id_arr_qita[] = $value1['id'];
					break;
			}
		}
		$this->diff_area($i,$product_arr_huadong,$id_arr_huadong,'华东');
		$this->diff_area($i,$product_arr_huabei,$id_arr_huanan,'华北');
		$this->diff_area($i,$product_arr_huanan,$id_arr_huadong,'华南');
		$this->diff_area($i,$product_arr_qita,$id_arr_qita,'其他');
	}
	public function diff_area($i,$product_arr,$ids,$china_area){
		// p($i);die;
		if(empty($ids)){
			$ids = "''";
		}else{
			$ids = implode(',', $ids);
		}
		$today_time = strtotime('today');
		$product = $this->db->model('offers_msg')->getAll('SELECT a.`id`,a.`grade`,a.`sale_price`,a.`china_area` FROM p2p_offers_msg AS a,(SELECT MAX(id) AS id,grade FROM p2p_offers_msg GROUP BY grade) b
			WHERE a.`id`=b.`id` AND a.`grade`=b.`grade` AND a.`status` = 2 and a.`input_time` > '.$today_time.' and a.id in ('.$ids.')');
// showtrace();
		// p($product);die;
		$res = $this->db->model('customer')->where("customer_manager>0 and status <> 9 and msg = 2 and (need_product <> '' OR need_product_adm <> '') AND china_area = '".$china_area."'")->select('c_id,c_name,need_product_adm,need_product,china_area')->limit($i*$this->nlimit.",".$this->nlimit)->getAll();
			// echo $this->db->getLastSql();
			// showtrace();
			// p($res);die;
			$need_product = array();
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
				$need_product[$value['c_id']]=array_filter(array_values(array_unique(array_merge($need_product_temp,$need_product_adm_temp))));
				//所需牌号与发布报价的牌号交集
				$same_product = array_values(array_intersect($need_product[$value['c_id']],$product_arr));//取交集
				//如果相同牌号不为空，程序才执行
				if(!empty($same_product)){
				// p($need_product);
					foreach ($same_product as $key2 => $value2) {
						foreach ($product as $key3 => $value3) {
							if(trim($value2) == trim($value3['grade'])){
								$same_product1[$key2]['id'] = trim($value3['id']);
								$same_product1[$key2]['grade'] = trim($value3['grade']);
								$same_product1[$key2]['sale_price'] = trim($value3['sale_price']);
								break;
							}
						}
					}
					$same_product2 = $same_product1;
					unset($same_product1);
					$same_product2 = array_filter($same_product2);
					// p($same_product2);die;
					if(!empty($same_product2)){
						$this->sendMsg($value['c_id'],$same_product2);
						unset($same_product2);
					}
				}
			}
	}
	//根据客户id获取联系人
	public function sendMsg($c_id,$offers_info){	 
		$res = $this->db->model('customer')->getAll("SELECT c1.`user_id`,c1.`name` as contact_name,c1.`c_id`,c1.`mobile` AS contact_mobile,customer_manager,adm.`name`,adm.`mobile` FROM `p2p_customer_contact` AS c1
			LEFT JOIN p2p_admin AS adm ON c1.`customer_manager` = adm.`admin_id`
			LEFT JOIN `p2p_adm_role_user` AS `user` ON `user`.`user_id` = c1.`customer_manager`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = `user`.`role_id`
			WHERE c1.`status` = 1 AND c1.`customer_manager` > 0 AND role.`pid` = 22 AND adm.`status` = 1 AND adm.`mobile` <> '' AND c1.`name` <> '' AND c1.`mobile` <> '' and c1.`c_id` = ".$c_id);
		// showtrace();
		if(!$res){
			return;
		}
		foreach ($offers_info as $k => $v) {

			$id_arr[] = $v['id'];
			$grade_arr[] = $v['grade'];
			$sale_price_arr[] = $v['sale_price'];
		}
		$offers_ids_str =implode(',',$id_arr);
		$grade =implode('、',$grade_arr);
		$sale_price =implode('元/吨、',$sale_price_arr).'元/吨';

		$date = date("m月d日",time());
		foreach ($res as $key => $value) {
			if(is_mobile($value['contact_mobile'])){
				$msg = sprintf(L('offers_sms.offers'),$grade,$sale_price,$date,$value['name'],$value['mobile']);
	    		M('system:sysSMS')->send($value['user_id'],$value['contact_mobile'],$msg,12,0,$offers_ids_str);
	    	}
		}
	}
}