<?php
/**
 * 身份证在线检查类
 **/
class idcardOnline{
	private $client=''; //查询对象

	/**
	 * 设置检查对象
	 * @access public 
	 */
	public function __construct() {
		$setting_query=getSystemParam('query'); //支付渠道
		$class='idcard'.($setting_query['idp']['channel']?:'Fake');
		$config=$setting_query['idp'][$setting_query['idp']['channel']];
		$this->client=new $class($config);
	}
	
	/**
	 * 在线检查
	 * @access protected 
	 * @param  string $real_name 姓名
	 * @param  string $id_card 身份证号
	 * @return array('result','error')
	 */
	public function checkOnline($real_name='',$idcard=''){
		$status=$this->client->set($real_name,$idcard)->checkOnline();
		$error=$this->client->getError();
		return array('status'=>$status,'error'=>$error);
	}

	/**
	 * 设置检查对象
	 * @access protected 
	 * @return int
	 */
	public function checkBalance(){
		return $this->client->checkBalance();
	}
}

class idCardBase{
	protected $idcard='';
	protected $real_name='';
	public $error=''; //错误信息
	
	/**
	 * 设置检查对象
	 * @access protected 
	 * @param  string $real_name 姓名
	 * @param  string $id_card 身份证号
	 * @return object
	 */
	public function set($real_name='',$idcard=''){
		$this->real_name=$real_name;
		$this->idcard=strtoupper($idcard);
		return $this;
	}
	
	/**
	 * 获取错误信息
	 * @access public 
	 * @param string
	 */
	public function getError(){
		return $this->error;	
	}

	/**
	 * 写查询日志
	 * @access protected 
	 * @param string $string 日志内容
	 */
	protected function _log($string=''){
		wlog(CACHE_PATH.'log/idcard/line.log', date("Y-m-d H:i:s")."\r\n".$this->real_name.':'.$this->idcard.'='.$string."\r\n\r\n");
	}
}

/**
 * 模拟查询
 */
class idcardFake extends idCardBase{
	public function checkOnline(){
		return 3;
	}
	public function checkBalance(){
		return 9999;
	}
}
/**
 * 爰金-联网检查身份证
 * @access private 
 * @return int
 */
class idcardYuanJin extends idCardBase{
	private $cred=''; //公用请求参数
	private $client=NULL; //请求对象
	private $url='http://service.sfxxrz.com/IdentifierService.svc?wsdl'; //请求地址
	public function __construct(array $config) {
		//公用请求参数：配置账户
		if(!empty($config)){
			//$this->url=$config['query_url'];
			$this->cred='{"UserName":"'.$config['username'].'","Password":"'.$config['password'].'"}';
		}
		$this->client=new SoapClient($this->url);
	}
	
	/**
	 * 联网检查身份证
	 * @access public 
	 * @return int
	 */
	public function checkOnline(){
		$param=array('request'=>'{"IDNumber":"'.$this->idcard.'","name":"'.$this->real_name.'"}','cred'=>$this->cred);
		try{
			set_time_limit(60);
			$result = $this->client->simpleCheckByJson($param)->SimpleCheckByJsonResult;
			
			$this->_log('('.__CLASS__.')'.$result);
			
			$result=json_decode($result,true);
			if($result['ResponseCode']=='100'){ //查询成功
				if($result['Identifier']['Result']=='一致'){ //一致
					return 3;	
				}elseif($result['Identifier']['Result']=='不一致'){ //不一致
					$this->error='身份证号码和姓名不一致';
					return 2;	
				}else{ //库中无此号
					$this->error='身份证库中无此号码';
					return 1;	
				}
			}else{ //查询失败
				$this->error='查询出错：'.$result['ResponseText'];
				return 0;
			}
		}catch(Exception $e) {
			$this->error=$e->getMessage();
			$this->_log('('.__CLASS__.')'.$this->error);
		}
		return 0;
	}
	
	/**
	 * 检查余额
	 * @access public 
	 * @return int
	 */
	public function checkBalance(){
		$param=array('request'=>'','cred'=>$this->cred);

		try{
			set_time_limit(60);
			$result = $this->client->QueryBalance($param)->QueryBalanceResult;
			$result=json_decode($result,true);
			$balance=$result['SimpleBalance'];
			return $balance;
		}catch(Exception $e) {
			$this->error=$e->getMessage();
		}
		return -1;
	}
}

/**
 * 满源-联网检查身份证
 * @access private 
 * @return int
 */
class idcardManyuan extends idCardBase{
	private $url='http://121.40.136.150:8080/IdInDataAu/api/authenInfoApi.htm'; //请求路径
	private $userId=0; //用户ID
	private $md5_key='';
	private $des_key='';
	public function __construct($config) {
		//公用请求参数：配置账户
		if(!empty($config)){
			$this->userId=$config['user_id'];
			$this->md5_key=$config['md5_key'];
			$this->des_key=$config['des_key'];
		}
	}
	
	/**
	 * 联网检查身份证
	 * @access public 
	 * @return int
	 */
	public function checkOnline(){
		$des=new desSecurity($this->des_key);
		$param=array(
			'userId'=>$this->userId,		 
			'coopOrderNo'=>date("YmdHis").mt_rand(1000,9999),		 
			'auName'=>$this->real_name,		 
			'auId'=>$this->idcard,
			'reqDate'=>date("Y-m-d H:i:s"),
			'ts'=>time(),
			'Sign'=>'',
		);
		
		$string='';
		$url=$this->url.'?';
		foreach($param as $k=>$v){
			if(empty($v)) continue;
			if($k!='reqDate'){
				$string .= $k.$v;
			}
			if($k=='auName' || $k=='auId'){
				$v=$des->encrypt($v);
			}			
			$url .= $k.'='.str_replace('+', '%20',urlencode(($v))).'&'; //URLEncode(GBK编码)
		}
		$sign=md5($string.$this->md5_key);
		$url .= 'sign='.$sign;
		
		try{
			set_time_limit(60);
			
			$result = httpPost($url);
			$result=gbk2utf($result);
			
			$this->_log('('.__CLASS__.')'.$result);
			
			//1-库中无此号, 2-不一致, 3-一致
			if(strstr($result,'<auResultInfo>一致</auResultInfo>')){
				return 3;
			}elseif(strstr($result,'<auResultInfo>库中无此号</auResultInfo>')){
				$this->error='身份证库中无此号码';
				return 1;	
			}elseif(strstr($result,'<auResultInfo>不一致</auResultInfo>')){
				$this->error='身份证号码和姓名不一致';
				return 2;	
			}else{
				preg_match('/<auResultInfo>(.*?)<\/auResultInfo>/',$result,$msg);
				$this->error='查询出错：'.$msg[1];
				return 0;
			}
		}catch(Exception $e) {
			$this->error=$e->getMessage();
			$this->_log('('.__CLASS__.')'.$this->error);
		}
		return 0;
	}

	/**
	 * 检查余额
	 * @access public 
	 * @return int
	 */
	public function checkBalance(){
		$this->error='暂不支持';
		return -1;
	}
}

/**
 * nciic-联网检查身份证
 * @access private 
 * @return int
 */
class idcardNciic extends idCardBase{
	private $username=''; //用户ID
	private $license=''; //证书
	private $client=NULL; //请求对象
	public function __construct(array $config) {
		if(!class_exists('SoapClient')){
			$this->error='不支持soap查询方法';
			return false;	
		}
		//公用请求参数：配置账户
		if(!empty($config)){
			$this->username=$config['username'];
			$this->license=$config['license'];
		}
		//公用请求参数：配置账户
		$this->client=new SoapClient(APP_LIB."model/NciicServices.wsdl");
	}
	
	/**
	 * 联网检查身份证
	 * @access public 
	 * @return int
	 */
	public function checkOnline(){
		$param = array(
			'inLicense' => $this->license, 
			'inConditions'=>'<?xml version="1.0" encoding="UTF-8"?><ROWS><INFO><SBM>'.$this->username.'</SBM></INFO><ROW><GMSFHM>公民身份号码</GMSFHM><XM>姓名</XM></ROW><ROW FSD="100022" YWLX="开户">
						<GMSFHM>'.$this->idcard.'</GMSFHM><XM>'.$this->real_name.'</XM></ROW></ROWS>',
		);
		
		try{
			set_time_limit(60);
			$result = $this->client->nciicCheck($param);
			$result = $result->out;
			
			$this->_log('('.__CLASS__.')'.$result);
			
			//1-库中无此号, 2-不一致, 3-一致
			if(strstr($result,'<result_gmsfhm>一致</result_gmsfhm>')){
				if(strstr($result,'<result_xm>一致</result_xm>')){ //完全一致
					return 3;	
				}else{ //不一致
					$this->error='身份证号码和姓名不一致';
					return 2;	
				}			
			}elseif(strstr($result,'<errormesage>')){ //无此号
				$this->error='身份证库中无此号码';
				return 1;	
			}elseif(strstr($result,'<ErrorMsg>')){ //检查错误
				//preg_match('/<ErrorCode>(.*?)<\/ErrorCode>/',$result,$code);
				preg_match('/<ErrorMsg>(.*?)<\/ErrorMsg>/',$result,$msg);
				$this->error='查询出错：'.$msg[1];
				return 0;
			}
		}catch(Exception $e) {
			$this->error=$e->getMessage();
			$this->_log('('.__CLASS__.')'.$this->error);
		}
		return 0;
	}

	/**
	 * 检查余额
	 * @access public 
	 * @return int
	 */
	public function checkBalance(){
		$this->error='暂不支持';
		return -1;
	}
}
?>
