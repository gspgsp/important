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
		$this->SavePurchase();
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
	
	/**
	 * 处理资源库手机号和QQ号同步
	 * @Author   xqj
	 * @DateTime 2016-09-23
	 */
	private function SavePurchase(){
	    set_time_limit(0);
	    $sql="SELECT  id,content FROM p2p_resourcelib2 WHERE is_fabu=0 LIMIT 0,10000";
	    $list =$this->db->getAll($sql);
	    $pur_model = M('product:purchase');
	    $fac_model = M('product:factory');
	    $pro_model = M('product:product');
	    foreach ($list as $k => $v) {
	        $exists = $this->db->model('resourcelib')->select('COUNT(0)')->where("id = {$v['id']}")->getOne();
	        if($exists<=0){//不存在 则插入记录
	        }else{//存在更新记录
	            $array = explode( '/', $v['content'] );
	            if(count($array)==6){
// 	                var_dump( explode( '/', $v['content'] ) );	    
	                $arr = array(
	                    'model'=>$array[0],
	                    'f_name'=> $array[1],
	                    'store_house'=>$array[2],
	                    'price'=>$array[3],
	                    'type'=> strstr($array[4],'购')? '1':'2', //采购或报价
	                    'number'=>$array[5],
	                    'user_id'=>'9266',
	                    'cargo_type'=>'1',
	                    'remark'=>'采购',
	                    'pt'=>'',
	                    'content'=>'',
	                );
	                $data = array('data'=>$arr,);
	                foreach ($data as $key => $value) {
	                    //是否已有该产品
	                    $model = $this->db->from('product p')
	                    ->join('factory f', 'p.f_id=f.fid');
	                    $where = "p.model='{$value['model']}'  and f.f_name='{$value['f_name']}'";
	                    $pid = $model->where($where)->select('p.id')->getOne();
	                    $soms = M('plasticzone:plasticPerson')->select('c_id,customer_manager')->where('user_id=' . $value['user_id'])->getRow();
	    
	                    $_data = array(
	                        'user_id' => $value['user_id'],//用户id
	                        'c_id' => $soms['c_id'],//客户id
	                        'customer_manager' => $soms['customer_manager'],//交易员
	                        'number' => $value['number'],//吨数
	                        'unit_price' => $value['price'],//单价
	                        'provinces' => $value['provinces'],//省份id
	                        'store_house' => $value['store_house'],//仓库
	                        'cargo_type' => $value['cargo_type'],//现货期货
	                        'period' => $value['period'],//期货周期
	                        'bargain' => $value['bargain'],//是否实价
	                        'type' => $value['type'],//采购、报价
	                        'sync' => 1,//报价来源平台
	                        'quan_type' => 1,
	                        'status' => 2,//状态，报价不需要审核，采购需要审核
	                        'input_time' => CORE_TIME,//创建时间
	                        'remark' => $value['remark'],//备注
	                        'content' => str_replace(PHP_EOL, '', $content),//客户直接填写的求购内容
	                    );
	    
	                    if ($pid) {
	                        //已有产品直接添加采购信息
	                        $_data['p_id'] = $pid;//产品id
	                        $pur_model->add($_data);
	                    } else {
	                        //                             //没有产品则新增一个产品
	                        //                             $pur_model->startTrans();
	                        //                             try {
	                        //                                 // 是否已有厂家
	                        //                                 $f_id = $fac_model->where("f_name='{$value['f_name']}'")->select('fid')->getOne();
	                        //                                 if (!$f_id) {
	                        //                                     //创建新厂家
	                        //                                     $_factory = array(
	                        //                                         'f_name' => $value['f_name'],//厂家名称
	                        //                                         'input_time' => CORE_TIME,//创建时间
	                        //                                     );
	                        //                                     if (!$fac_model->add($_factory)) throw new Exception("系统错误 pubpur:101");
	                        //                                     $f_id = $fac_model->getLastID();
	                        //                                 }
	                        //                                 $_product = array(
	                        //                                     'model' => $value['model'],//牌号
	                        //                                     'product_type' => $value['product_type'],//产品类型
	                        //                                     'process_type' => $value['process_level'],//加工级别
	                        //                                     'input_time' => CORE_TIME,//创建时间
	                        //                                     'f_id' => $f_id,//厂家id
	                        //                                     'status' => 3,//审核状态
	                        //                                 );
	                        //                                 if (!$pro_model->add($_product)) throw new Exception("系统错误 pubpur:102");
	                        //                                 $pid = $pro_model->getLastID();
	                        //                                 $_data['p_id'] = $pid;
	                        //                                 if (!$pur_model->add($_data)) throw new Exception("系统错误 pubpur:103");
	                        //                             } catch (Exception $e) {
	                        //                                 $pur_model->rollback();
	                        //                                 $this->error($e->getMessage());
	                        //                             }
	                        //                             $pur_model->commit();
	                    }
	                    $this->db->model('resourcelib2')->where("id = {$v['id']}")->update(array('is_fabu'=>1,));
	                }
	    
	            }
	    
	        }
	    }
	}
}