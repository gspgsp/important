<?php
/** 
 * app公用控制器
 * Andy@2014-06-03
 */
class appAction extends action {
	private $ssid='';  //当前的ssid
	protected $token='';  //当前token
	protected $userid=''; //当前操作userid	
	protected $deviceid='';  //设备ID
	protected $version='';  //版本号
	protected $chanel='';  //渠道
	protected $uinfo=array(); //当前用户信息
	protected $output=array(); //待输出参数

	protected $chk_login = false;//是否需要登录
	protected $not_need_login = array();//不用登录的method
	protected $need_login = array();//需要登录的method

	protected $sys = null;
	protected $is_ajax = true;
	/**
	 * APP父类构造函数
	 * @access public 
	 */
	public function __construct() {
		wlog(CACHE_PATH.'log/app.log',date("Y-m-d H:i:s")." Request:\r\n".json_encode($_REQUEST)."--".get_ip()."\r\n\r\n");
		C('DES_PASSCODE','98186835');
		$userid = sget('userid','i',0);
		$this->version = sget('version','s','');
		$this->sys = M('system:setting')->getSetting();
		$this->chanel = array_search(sget('chanel','s','touch'), L('user_chanel')) ?: 2; //7-andriod,8-ios

		$this->_deviceidInit();

		$this->userid = (int)$_SESSION['userid'];
		if(empty($this->userid) || ($userid && $userid != $this->userid)){ //检查令牌
			$this->_tokenInit();
			if($this->userid){
				M('user:passport')->setSession($this->userid);
				M('user:passport')->loginSuccess($this->userid,$this->chanel);//token登录成功，记录日志
				$_SESSION['token'] = $this->token;
			}elseif(($this->chk_login && !in_array(ROUTE_A,$this->not_need_login)) || (!$this->chk_login && in_array(ROUTE_A,$this->need_login))){
				$this->error('用户未登录');
			}
		}else{
			$this->token = $_SESSION['token'];
		}
		$this->uinfo=$_SESSION['uinfo'];
	}

	/**
     * 获取请求参数
     * @access protected
     * @param array $param 请求参数
     * @return void
     */
	protected function getParam($param=array()){
		foreach($param as $k=>$v){
            $type = isset($v['type']) ? strtolower($v['type']) : 's'; //s-string,f-float,i-int,a-array
            $need = isset($v['need']) ? strtolower($v['need']) : 'y'; 
            $msg = isset($v['msg']) ?  $v['msg'] : "[$k]不符合规则";
            $rule = isset($v['rule']) ?  strtolower($v['rule']) : '*';
			
			$data=sget($k,$type);
			if($need=='y' && empty($data) || $data && !$this->_valid($data,$rule)){
				$this->output($msg,3);
			}
			
			$param[$k]=$data;
		}
		return $param;
	}
	
	/**
     * 检查弱密码
     * @access private
     * @param array $string 密码原文
     * @return bool
     */
	protected function _weakPasswd($string=''){
		if(!preg_match('/(.)\\1{6,19}/i',$string)){ //字符相同
			for($i=1;$i<strlen($string);$i++){ //升序或降序
				if(abs(ord($string[$i-1])-ord($string[$i]))!=1){
					return false;	
				}	
			}
		}
		return true;
	}
	
	/**
     * 操作错误的方法
     * @access protected
     * @param string $msg 错误信息
     * @return void
     */
	protected function error($msg=''){
		$this->output($msg,1);
	}
	
	/**
     * 操作成功的方法
     * @access protected
     * @param string $msg 返回提示信息
     * @return void
     */
	protected function success($msg='OK'){
		$this->output($msg,0);
	}
	
	/**
     * 必须要校验token信息
     * @access protected
     */
	protected function chkToken(){
		if(empty($this->token) && empty($this->userid)){
			$this->output('Token信息不正确或已过期',99);
		}
	}

    public function __set($name,$value) { 
    }
    public function __get($name) {
    }
	/**
	 * APP输出结果
	 * @access private 
     * @param string $msg 错误信息
     * @param int $err 状态
     * @return json
	 */
	public function output($msg='',$err=0){
		header('Access-Control-Allow-Origin:*');
		$result=array(
			'err'=>$err,			  
			'msg'=>$msg,			  
		);
		$this->output['ssid']=$this->ssid; //所有输出追加ssid 
		$result=json_encode(array_merge($result,$this->output));
		$jsoncallback=sget('callback');
		if(!empty($jsoncallback)){
			header('Content-type:text/javascript; charset=utf-8');
			$result=$jsoncallback."($result)";
		}else{
			header('Content-type:application/json; charset=utf-8');
		}
		//wlog(CACHE_PATH.'log/app.log',date("Y-m-d H:i:s")." Output:\r\n".$result."\r\n\r\n");
		die($result);
	}

	/**
     * 校验传入的参数是否符合规则
     * @access private
     * @param string $data 待校验的值
     * @param string $rule 待校验的正则规则
     * @return bool
     */
    private function _valid(&$data='',$rule='*') {
        static $validate = array(
			'match'=>'/^(.+?)(\d+)-(\d+)$/', //可以扩展的格式n1-n2
            'normal' => '/^\d+$/', //自然数
            'integer' => '/^[-\+]?\d+$/', //正负整数
            'float' => '/^[-\+]?\d+(\.\d+)?$/', //正负浮点数
			'qq'=>'/^[1-9]\d{4,10}$/',  //QQ号码
            'zip' => '/^[1-9]\d{5}$/',
            'english' => '/^[A-Za-z]+$/', //纯英文
            'chinese' => '/^[\\x4E00-\\x9FA5\\xF900-\\xFA2D]+/', //纯中文
            'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', //邮箱
			'mobile'=>'/^1[3-8]\d{9}$/', //手机号
			'idcard'=>'/^(\s)*(\d{15}|\d{18}|\d{17}x)(\s)*$/i',  //身份证
            'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/', //url地址
            '*' => '/^[\w\W]+$/', //所有符号
            'passwd' => '/^[\w\W]+$/', //所有符号
			's'=>'/^[\\x4E00-\\x9FA5\\xF900-\\xFA2D\w\.\s]+$/', //中英文(非空)
			'date' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/',
			'time' => '/^([0-9]|[01][0-9]|2[1-3])(:([0-5]?[0-9])){1,2}$/',
        );

		//追加s1-n格式的正则表达
		if(!isset($validate[$rule]) && preg_match($validate['match'],$rule,$mac)){
			if(isset($validate[$mac[1]])){
				$validate[$rule]=str_replace("+$/",'',$validate[$mac[1]]).'{'.$mac[2].','.$mac[3].'}$/';
			}
		}

		//客户端密码需要解密
		if(strpos($rule,'passwd') === 0){
			switch($this->chanel){
				case 8://ios 版本号小于2不加密
					if($this->version >= 2){
 						$data = desDecrypt_IOS($data);//IOS处理
					}
					break;
				case 7://android
				case 9://wp
					$data=desDecrypt($data);
			}
		}

		//校验正则
        if(isset($validate[strtolower($rule)])){
            $rule = $validate[strtolower($rule)];
        	return preg_match($rule,$data)===1;
		}else{
			return false;
		}
    }
	
	/**
	 * 检查deviceid
	 */
    private function _deviceidInit(){
    	$this->deviceid = sget('deviceid','s');
		if(ROUTE_A != 'deviceid' && !preg_match('/^[a-f0-9\-]{30,40}$/i', $this->deviceid)){
			$this->output('deviceid不正确',99);
		}else{
			appSession::start(sget('ssid','s'), $this->deviceid, sget('chanel','s'));
			$this->ssid = appSession::$SSID;
		}
    }

	/**
     * 初始化TOKEN信息
     * @access private
     */
	private function _tokenInit(){
		$token=sget('token','s','');
		$userid=sget('userid','i',0);
		if(strlen($token)>30){ //需要校验用户身份
			$model=M('app:token');
			$result=$model->chkToken($token,$userid);
			if($result['err']>0){
				$this->error($result['msg']);	
			}else{
				$this->token=$token;
				$this->uinfo=$model->getInfo();
				$this->userid=$this->uinfo['user_id'];
			}
		}
	}
	
	/**
	 * 空方法不报错
	 * @access public 
	 */
	public function _null(){ //
		$this->error('错误的接口请求');
	}
}
?>
