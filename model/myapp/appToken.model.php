<?php
/**
 *塑料圈app token类
 * created by zhanpeng
 * copy token类
 * 防止产生对别的影响
 */
class appTokenModel extends model
{
    private static $MUID=999869523; //最大UID
    private $token='';  //当前token
    private $userid=''; //当前操作userid
    private $uinfo=array(); //当前用户信息
    private $_life  = 2592000; //过期时间一个月

    public function __construct() {
        parent::__construct(C('db_default'), 'app_token');
    }

    /**
     * 清空token
     * @access public
     * @param int userid 用户UID
     * return string
     */
    public function destory($token=''){
        if($token) $this->token = $token;
        return $this->where("token='".$this->token."'")->update(array('token'=>'','expiry'=>''));
    }
    /**
     * 插入用户token信息
     * @access public
     * @param int userid 用户UID
     * return string
     */
    public function insert($userid=0,$user=array()){
        $cache=cache::startMemcache();
        //$token=$cache->get('qapp'.$userid);
        $appToken = $this->model('app_token')->where('userid='.$userid)->getRow();
        if(CORE_TIME <= $appToken['expiry']){//meiyou过期
            $cache->set($appToken['token'],$userid,864000);//10天
            return $appToken['token'];
        }else{
            if(empty($appToken['data'])){//新用户
                $this->userid=$userid;
                $this->token=$this->_genToken($userid);
                if(empty($user))
                    $user=$this->model('customer_contact')->wherePk($userid)->getRow();
                $this->uinfo=$user;
                $data=array(
                    'token'=>$this->token,
                    'expiry'=>CORE_TIME+$this->_life,
                    'userid'=>$this->userid,
                    'module'=>ROUTE_C.':'.ROUTE_A,
                    'data'=>json_encode($this->uinfo),
                );
                $this->model('app_token')->add($data);
                $cache->set($this->token,$userid,864000);//10天
                return $this->token;
            }else{//老用户
                $this->userid=$userid;
                $this->token=$this->_genToken($userid);
                if(empty($user))
                    $user=$this->model('customer_contact')->wherePk($userid)->getRow();
                $this->uinfo=$user;
                $data=array(
                    'token'=>$this->token,
                    'expiry'=>CORE_TIME+$this->_life,
                    'module'=>ROUTE_C.':'.ROUTE_A,
                    'data'=>json_encode($this->uinfo),
                );
                $this->model('app_token')->where('userid='.$this->userid)->update($data);
                $cache->set($this->token,$userid,864000);//10天
                return $this->token;
            }
        }
    }
    /**
     * 生成用户身份Token
     * @access public
     * @param int userid 用户UID
     * return string
     */
    private function _genToken($userid=''){
        $user=$this->model('customer_contact')->wherePk($userid)->getRow();
        $_md5=md5($userid.'|'.substr($user['password'],6,16));
        $_pos=$this->_pos($_md5); //替换开始位置

        $_uid=str_pad(dechex(self::$MUID-$userid),8, "0", STR_PAD_LEFT); //换算过的uid
        $token=substr($_md5,0,$_pos).$_uid.substr($_md5,$_pos+8);
        return $this->_fix($token); //补第一位校验位
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
    //反编译用户的id
    public function deUserId($token){
        if(strlen($token)>30){
            $result=$this->_fix($token,true);
            if(empty($result)){
                return array('err'=>1,'msg'=>'Token校验错误');
            }
            $cache=cache::startMemcache();
            $_userid=$cache->get($token);
            if(empty($_userid)) $_userid=$this->model('app_token')->select('userid')->where("token='".$token."'")->getOne();
            if(empty($_userid))  return array('err'=>1,'msg'=>'已退出,请重新登录');
            return $_userid;
        }
        return array('err'=>1,'msg'=>'Token参数不正确');
    }
}