<?php
/**
 * 账户身份证检查 
 */
class sysIdCardModel extends model{
	private $des=NULL; 
	private $user_id=0; //查询用户ID
	private $real_name=''; //查询姓名
	private $idcard=''; //身份证号码
	private $des_idcard=''; //身份证号码（密文）
	
	public function __construct() {
		parent::__construct(C('db_default'), 'lib_idcard');
		$this->des= new desSecurity('95816390');
	}
	
	/**
	 * 设置检查对象
	 * @access public 
	 * @param  string $real_name 姓名
	 * @param  string $id_card 身份证号
	 * @param  int $user_id 用户id
	 * @return object
	 */
	public function set($real_name='',$idcard='',$user_id=0){
		$this->user_id=$user_id;
		$this->real_name=$real_name;
		$this->idcard=strtoupper($idcard);
		$this->des_idcard=$this->encrypt($this->idcard);
		return $this;
	}
	
	/**
	 * 用户注册身份证
	 * @access public 
	 * @return bool
	 */
	public function register(){
		if($this->user_id<1){
			$this->error='请检查是否登录';
			return false;
		}
		
		//常规检查
		if(!$this->_chkNormal()){
			return false;
		}
		
		//检查是否存在
		if($this->chkExist($this->des_idcard,1)){
			$this->error='身份证号已存在';
			return false;
		}
		
		//查询库和联网查询
		if(!$this->libCheck()){
			return false;	
		}
		
		$data=array(
			'real_name'=>$this->real_name,
			'id_type'=>1,
			'sex'=>$this->getSex($this->idcard),
			'birthday'=>$this->getAge($this->idcard),
			'id_card'=>$this->des_idcard, //密文
			'last_edit'=>CORE_TIME, //最后操作时间
			'chk_idcard'=>1,
		);
		return $this->model('user_info')->where("user_id=".$this->user_id)->update($data);
	}
	
	/**
	 * 查询库中的身份证
	 * @access public 
	 * @return bool
	 */
	public function libCheck(){
		//常规检查
		if(!$this->_chkNormal()){
			return false;
		}
		
		//检查身份证号
		$info=$this->model('lib_idcard')->where("idcard='".$this->des_idcard."'")->order('check_status desc')->getAll();
		if(!empty($info)){
			$check_num=0; //同一身份查询次数
			foreach($info as $k=>$v){
				if($v['check_status']==3){ //检查一致
					if($v['real_name']==$this->real_name){ //完全符合
						return true;
					}else{
						$this->error='检查不一致';
						return false;
					}				
				}elseif($v['check_status']==2){ //检查不一致
					if($v['real_name']==$this->real_name){ //依然不一致
						$this->error='检查不一致';
						return false;
					}else{ //换了姓名，可以进一步检查
						$check_num++;
					}				
				}elseif($v['check_status']==1){ //库中无此号
					$this->error='库中无此号';
					return false;
				}				
			}			
			if($this->user_id>0 && $check_num>=3){ //查询超次数
				$this->error='同一身份证查询不能超过3次';
				return false;
			}
		}
		
		//同一用户只能查询2次
		if($this->user_id>0){
			$count=$this->chkTimes($this->user_id);
			if($count>=2){
				$this->error='同一用户最多能查询2次，请与客服联系';
				return false;	
			}
		}
		
		//开始联网检查
		$result=$this->chkPolice();
		if($result>0){ //数据入库
			$data=array(
				'idcard'	=> $this->des_idcard,	
				'real_name'	=> $this->real_name,	
				'check_time'	=> CORE_TIME,	
				'check_status'	=> $result,	
				'ip' => get_ip(),
				'user_id' => $this->user_id, 
			);
			$this->model('lib_idcard')->add($data);
		}
		if($result==3){ //完全一致
			return true;
		}else{
			return false;	
		}
	}
	
	/**
	 * 查询用户查询次数
	 * @access public 
	 * @param  int $user_id 用户id
	 * @return int
	 */
	public function chkTimes($user_id=0){
		return $this->model('lib_idcard')->select('count(id)')->where('user_id='.$user_id)->getOne();
	}
	
	/**
	 * 联网检查身份证
	 * @access private 
	 * @return int
	 */
	private function chkPolice(){
		if(!class_exists('SoapClient')){
			$this->error='不支持soap查询方法';
			return false;	
		}
		
		return $this->_yuanJinPolice();
		
		//加载库
		$client = new SoapClient(APP_LIB."model/NciicServices.wsdl");
		$param = array(
			'inLicense' => '?v?zV`bhbg+sO?.I:Tal%Ods8&?c>i?g;q?h?o2lA3%I;%C-@3;_?+){?c:rHhTwKz?o7.%uHS?vb`MbFn]rQ[bXPq?x7sEjSzIdKtLnd9DcJn?x?vHvQtQrIf?x?.?d?g$q:/$r?jFfDdIzM/?x?vSaFgFh`3?xd)?d&e?g?s:m?jc:^w@/Tj?x?vBrb9a7Xi/k4&<&+%MHRZ0#5E@V(i(k?h0z?g?v?j^bZlBsVl?x?vQxPkKraCVxYwVbb5?xc=?g?g?g?g?g=x?jAkDnDePv^/^k\gBm?x?vPtbC%a?x?vcOcMJtEzKtKc@gBbSi/k?v^fWlEeUv@/EmPqA.Es@mAg.j2gKfUcDf]t?x?g*u?g3x-k?jU;Qmc9M/?x?v`}HuXoaVE;BsZtWlToJbDcSi-;?v1p]kRuK.OaMvYaGc$y', 
			'inConditions'=>'<?xml version="1.0" encoding="UTF-8"?><ROWS><INFO><SBM>bjssbjss49001</SBM></INFO><ROW><GMSFHM>公民身份号码</GMSFHM><XM>姓名</XM></ROW><ROW FSD="100022" YWLX="开户">
						<GMSFHM>'.$this->idcard.'</GMSFHM><XM>'.$this->real_name.'</XM></ROW></ROWS>',
		);
		
		try{
			set_time_limit(60);
			$result = $client->nciicCheck($param);
			$result = $result->out;
			
			$this->_log($result);
			
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
			$this->_log($this->error);
		}
		return 0;
	}
	
	/**
	 * 身份证号是否已存在
	 * @access private 
	 * @param  string $id_card
	 * @param  int $id_type 证件类型:1身份证2机构代码
	 * @return bool
	 */
	public function chkExist($id_card='',$id_type=1,$user_id=0){
		$where = "id_type='$id_type' and id_card='$id_card'";
		if($user_id){
			$where .= " and user_id !='$user_id'";
		}
		$user_id=$this->model('user_info')->select('user_id')->where($where)->getOne();
		if($user_id>0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 获取加密身份证
	 * @access public 
	 * @param  string $idcard 用户id
	 * @return string
	 */
	public function encrypt($idcard=''){
		return $this->des->encrypt($idcard);
	}
	
	/**
	 * 获取解密身份证
	 * @access public 
	 * @param  string $idcard 用户id
	 * @return string
	 */
	public function decrypt($idcard=''){
		return $this->des->decrypt($idcard);
	}

	/**
	 * 获取解密身份证
	 * @access public 
	 * @param  string $idcard 用户id
	 * @return string
	 */
	public function hide($idcard=''){
		$idcard=$this->des->decrypt($idcard);
		//return substr_replace($idcard,'********',4,8); 
		return hideStr($idcard,4,2);
	}

	
	/**
	 * 常规检查
	 * @access private 
	 * @return bool
	 */
	private function _chkNormal(){
		//验证姓名
		if(!is_chinese($this->real_name)){
			$this->error='请输入正确的中文姓名';
			return false;
		}
		
		//合法性
		if(!$this->isIdCardNo($this->idcard)){
			$this->error='错误身份证';
			return false;
		}
		return true;
	}

	/**
	 * 验证身份证号合法性
	 * @param $idcard
	 * @return bool
	 */
	public function isIdCardNo($idcard=''){
		$vCity = array('11','12','13','14','15','21','22','23','31','32','33','34','35','36','37','41','42','43','44','45','46','50','51','52','53','54','61','62','63','64','65','71','81','82','91');
	
		if(!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $idcard)){
			return false;
		}
	
		if(!in_array(substr($idcard, 0, 2), $vCity)){ //首2位省份不符合
			return false;
		}
	
		$idcard = preg_replace('/[xX]$/i', 'a', $idcard);
		$vLength = strlen($idcard);
	
		if ($vLength == 18){
			$vBirthday = substr($idcard, 6, 4) . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
		} else {
			$vBirthday = '19' . substr($idcard, 6, 2) . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
		}

		//非法生日
		if(date('Y-m-d', strtotime($vBirthday)) != $vBirthday){
			return false;
		}
		
		if ($vLength == 18)	{
			$vSum = 0;	
			for ($i = 17 ; $i >= 0 ; $i--){
				$vSubStr = substr($idcard, 17 - $i, 1);
				$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}
			if($vSum % 11 != 1) return false;
		}
		return true;
	}
	
	function getAge($idcard){
		//过了这年的生日才算多了1周岁
        if(empty($idcard)) return false;
		//$idcard=$this->des->decrypt($idcard);
		$idcard = preg_replace('/[xX]$/i', 'a', $idcard);
		$vLength = strlen($idcard);
	
		if ($vLength == 18){
			$vBirthday = substr($idcard, 6, 4) . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
		} else {
			$vBirthday = '19' . substr($idcard, 6, 2) . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
		}
 
        return $vBirthday;
    } 
	
	function getSex($idcard){
		$sexint = (int)substr($idcard,16,1); 
		return $sexint % 2 === 0 ? '2' : '1';//1=>男，2=>女
	}
	
	/**
	 * 写查询日志
	 * @param string $string 日志内容
	 */
	private function _log($string=''){
		wlog(CACHE_PATH.'log/idcard/line.log', date("Y-m-d H:i:s")."\r\n".$this->real_name.':'.$this->idcard.'='.$string."\r\n\r\n");
	}
	
	/**
	 * 爰金-联网检查身份证
	 * @access private 
	 * @return int
	 */
	private function _yuanJinPolice(){
		$client=new SoapClient('http://service.sfxxrz.com/IdentifierService.svc?wsdl');
		$param=array('request'=>'{"IDNumber":"'.$this->idcard.'","name":"'.$this->real_name.'"}','cred'=>'{"UserName":"bjssr_admin","Password":"bjssr888"}');

		try{
			set_time_limit(60);
			$result = $client->simpleCheckByJson($param)->SimpleCheckByJsonResult;
			
			$this->_log($result);
			
			$result=json_decode($result,true);
			if($result['ResponseCode']=='100'){ //查询成功
				if($result['Identifier']['Result']=='一致'){ //一致
					return 3;	
				}elseif($result['Identifier']['Result']=='不一致'){ //不一致
					return 2;	
				}else{ //库中无此号
					return 1;	
				}
			}else{ //查询失败
				$this->error='查询出错：'.$result['ResponseText'];
				return 0;
			}
		}catch(Exception $e) {
			$this->error=$e->getMessage();
			$this->_log($this->error);
		}
		return 0;
	}
}
?>