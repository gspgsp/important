<?php
/** 
 * TOKEN操作类
 * Andy@2014-06-03
 */
class tokenModel extends model{
	private static $MUID=999869523; //最大UID
	private $token='';  //当前token
	private $userid=''; //当前操作userid	
	private $uinfo=array(); //当前用户信息
    private $_life  = 86400; //过期时间
	
	public function __construct() {
		parent::__construct(C('db_default'),'app_token');
	}
	
	/**
	 * 检查用户信息
	 * @access public 
	 * @param string token 用户口令
	 * @param int userid 用户UID
	 * return array(error,msg,uinfo)
	 */
	public function chkToken($token='',$userid=0){
		if(strlen($token)>30){
			$result=$this->_fix($token,true);
			if(empty($result)){
				return array('err'=>1,'msg'=>'Token校验错误');
			}
			
			$_pos=$this->_pos($token); //替换开始位置
			$_userid=self::$MUID-hexdec(substr($token,$_pos,8));
			if($userid>0 && $userid<>$_userid){
				return array('err'=>1,'msg'=>'Token校验错误');
			}
			
			$this->token=$token;
			$this->userid=$_userid;
		
			//读取token数据信息
			$this->_tokenInfo();
			
			if(empty($this->token)){
				return array('err'=>2,'msg'=>'Token校验错误');
			}
			$this->uinfo['userid']=$userid;
			return array('err'=>0,'msg'=>'校验成功','uinfo'=>$this->uinfo);
		}
		return array('err'=>3,'msg'=>'Token参数不正确');
	}
	
	/**
	 * 插入用户token信息
	 * @access public 
	 * @param int userid 用户UID
	 * return string
	 */
	public function insert($userid=0,$user=array()){
		$this->userid=$userid;
		$this->token=$this->_genToken($userid);
		
		if(empty($user))
			$user=$this->getUser($userid);
		$this->uinfo=$user;
		
		$data=array(
			'token'=>$this->token,
			'expiry'=>CORE_TIME+$this->_life,
			'userid'=>$this->userid,
			'module'=>ROUTE_C.':'.ROUTE_A,
			'data'=>json_encode($this->uinfo),
		);
		$this->model('app_token')->add($data,true);
		return $this->token;
	}

	/**
	 * 清空token
	 * @access public 
	 * @param int userid 用户UID
	 * return string
	 */
	public function destory($token=''){
		if($token) $this->token = $token;
		return $this->where("token='".$this->token."'")->delete();
	}

	/**
	 * 获取Token中保持的用户信息
	 * @access public 
	 * @param int userid 用户UID
	 * return array
	 */
	public function getInfo(){
		return $this->uinfo;
	}

	/**
	 * 检查用户信息
	 * @access public 
	 * @param int userid 用户UID
	 * return array
	 */
	public function getUser($userid=0){
		static $user=array();
		if(!isset($user[$userid])){
			$user[$userid]=$this->model('user')->select('user_id,password,mobile,email,last_login,visit_count')->getPk($userid);
		}
		return $user[$userid];
	}
	
	/**
	 * 生成用户身份Token
	 * @access public 
	 * @param int userid 用户UID
	 * return string
	 */
	private function _genToken($userid=''){
		$user=$this->getUser($userid);
		$_md5=md5($userid.'|'.substr($user['password'],6,16));
		$_pos=$this->_pos($_md5); //替换开始位置
		
		$_uid=str_pad(dechex(self::$MUID-$userid),8, "0", STR_PAD_LEFT); //换算过的uid
		$token=substr($_md5,0,$_pos).$_uid.substr($_md5,$_pos+8);
		return $this->_fix($token); //补第一位校验位
	} 
	
	/**
	 * 计算UID替换起始位置
	 * @access public 
	 * @param string token
	 * return int
	 */
	private function _pos($token=''){
		//最后一位数字+3位开始起的8位替换
		return hexdec(substr($token,-1))+3;
	}

	/**
	 * 获取用户Token信息
	 * @access private 
	 * return bool
	 */
	private function _tokenInfo(){
		$info=$this->model('app_token')->where("token='".$this->token."'")->getRow();
		if($info){
			if($info['userid']==$this->userid){ //更新请求
				$this->uinfo=json_decode($info['data'],true);			
				$data=array(
					'expiry'=>CORE_TIME+$this->_life,
					'module'=>ROUTE_C.':'.ROUTE_A,
				);
				return $this->model('app_token')->where("token='".$this->token."'")->update($data);
			}else{ //清除错误数据
				$this->model('app_token')->where("token='".$this->token."'")->delete();
				unset($info);
				return false;
			}
		}else{ //数据库中没有token[被清理]：则重新生成
			$_token=$this->_genToken($this->userid);
			if($this->token<>$_token){
				$this->token='';
				$this->userid=0;
				return false;	
			}
			$this->insert($this->userid);
			return true;
		}
	}
	
	/**
	 * token校验位
	 * @access private 
	 * return bool
	 */
	private function _fix($string='',$chk=false){
		if(strlen($string)!=32){
			return false;	
		}
		$sub=substr($string,1);
		
		$sum=1239;
		for($i=0;$i<31;$i++){
			$sum += hexdec($sub[$i]);
		}
		$pre=dechex($sum%16);
		
		if($chk && $pre!=substr($string,0,1)){
			return false;
		}
		return $pre.$sub;
	}
}
?>