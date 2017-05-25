<?php
/**
 * 账户信息 
 */
class userInfoModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'user_info');
	}
	
	/*
	 * 我的账户列表
	 * @access public
	 * @param int user_id 用户ID
     * @return array
	*/
	public function getUserInfo($user_id=0){
		$uinfo=$this->model('user_info')->where('user_id='.$user_id)->getRow();

		//安全等级:实名+银行卡(2)+交易密码+保护问题(low/mideum/high)
		$level=$uinfo['chk_idcard']+$uinfo['chk_bank']+$uinfo['has_paywd']+$uinfo['chk_safe'];
		if($level<2){
			$uinfo['sale_level']='low';
			$uinfo['sale_level_ui']='低';
		}elseif($level<4){
			$uinfo['sale_level']='mideum';
			$uinfo['sale_level_ui']='中';
		}else{
			$uinfo['sale_level']='high';
			$uinfo['sale_level_ui']='高';
		}

		$this->composeInfo($uinfo);
		
		return $uinfo;
	}

	/**
	 * 合成部分信息
	 * @param  array $uinfo 用户资料
	 * @return array
	 */
	public function composeInfo(array &$uinfo){
		//用户头像
		if ($uinfo['himg']) {
			$uinfo['himg'] = C('TEMP_REPLACE.__UPLOAD__').'/'.$uinfo['himg'];
		}

		//用户称呼
		$uinfo['call_name'] = '';
		if($uinfo['real_name']){
			$sex_titles = L('sex_titles');
			$uinfo['call_name'] = $uinfo['real_name'] ? sub_str($uinfo['real_name'],1,false).$sex_titles[$uinfo['sex']?:1] : '';
		}else{
			$user = $this->model('user')->getPk($user_id);
			$uinfo['call_name'] = hideStr($user['mobile'],3,2);
		}
		return $uinfo;
	}

	/*
	 * 获取用户身份证信息
	 * @access public
	 * @param int user_id 用户ID
     * @return array
	*/
	public function getUserIdCard($user_id=0){
		$info=$this->model('user_info')->select('chk_idcard,id_type,id_card,real_name')->where('user_id='.$user_id)->getRow();
		if($info['chk_idcard']){
			$info['id_card']=M('system:sysIdCard')->decrypt($info['id_card']);	
		}
		return $info;		
	}
	
	/**
	 * 更新用户资料
	 * @param int $user_id 用户ID
	 * @param array  $data    用户资料
	 * @return bool
	 */
	public function setUserInfo($user_id,array $data){
		return $this->model('user_info')->where('user_id='.intval($user_id))->update($data);
	}

	/*
	 * 我的账户列表
	 * @access public
	 * @param int user_id 用户ID
     * @return array
	*/
	public function getUserInfoByIdCard($realName="",$idCard=""){
		if($realName != "" && $idCard != ""){
			$info=$this->model('user_info')->where("real_name='$realName' and id_card='$idCard'")->getRow();
		}
			
		return $info;
	}

	/*
	 * 用户基本资料
	 * @access public
	 * @param int user_id 用户ID
     * @return array
	*/
	public function getUserInfoExt($user_id, $table=''){//table=company/agent
		return $this->model('user_info_ext'.$table)->where('user_id='.intval($user_id))->getRow();
	}

	/**
	 * 更新用户扩展资料
	 * @param int $user_id 用户ID
	 * @param array  $data    用户资料
	 * @return bool
	 */
	public function setUserExtInfo($user_id, array $data,$is_update=0){
		return $this->model('user_info_ext')->where('user_id='.intval($user_id))->update($data) && $this->getNumRows() || 
			   $this->model('user_info_ext')->add($data + compact('user_id'));
	}

	/**
	 * 设置用户安全问题
	 * @param int $user_id   用户id
	 * @param array  $questions 安全问题关联数组
	 * @return bool
	 */
	public function setQuestions($user_id,array $questions){
		$data = array('user_id'=>$user_id,'update_time'=>time());
		reset($questions);
		for($i = 1; $i < 4; $i++){
			list($data['question_'.$i],$data['answer_'.$i]) = each($questions);
			$data['answer_'.$i] = substr(md5($data['answer_'.$i]),0,-2);
		}
		return (bool)$this->model('user_question')->add($data,TRUE);
	}

	/**
	 * 获取用户设置的安全问题
	 * @param  int $user_id 用户id
	 * @return array
	 */
	public function getQuestions($user_id){
		return $this->model('user_question')->where('user_id='.$user_id)->getRow();
	}
	
	/**
	 * 获取用户账单信息
	 */
	public function getBill($user_id,$month_id){
		return $this->model('stat_bill')->where("user_id=$user_id AND month_id=$month_id")->getRow();
	}
	
	/**
	 * 获取用户推荐用户信息
	 */
    public function getRefUser($user_id=0,$time_start=0,$time_end=0,$page=1,$size=10){
		$where = "";
		if($time_start>0 && $time_end>0){
			$where .= " and ui.reg_time>{$time_start} and ui.reg_time<{$time_end}";
		}
		$list = $this->select("u.mobile,ui.real_name,ui.reg_time,ui.ref,u.chanel,u.user_id,ui.remarks,(select sum(n_in) from ".$this->table('usaccount')." where user_id=u.user_id and ac_type in(2,3)) invest_count")
				->from('user u')
				->join('user_info ui','u.user_id=ui.user_id')
				->where('ui.ref_uid='.$user_id.$where)
				->page($page,$size)
				->order('ui.user_id desc')
				->getPage();
		
		//$result=array('total'=>$list['count'],'data'=>$list['data']);
			
		return $list;
	}
	
	/**
	 * 获取所有用户推荐用户
	 */
	public function getRefUserById($user_id=0,$time_start=0,$time_end=0){
		$where = "";
		if($time_start>0 && $time_end>0){
			$where .= " and ui.reg_time>{$time_start} and ui.reg_time<{$time_end}";
		}
		$list = $this->select("ui.user_id")
				->from('user_info ui')
				->where('ui.ref_uid='.$user_id.$where)
				->order('ui.user_id desc')
				->getCol();
			
		return $list;
	}
	/*
	 * 获取推荐用户奖励金币的数量
	 * 每天批处理需要用到
	 * @access public
     * @return array
	*/
	public function getRecommentCoins($startTime=0,$endTime=0){
		//获取释怀通过推荐而来且通过了银行认证的数据列表
		$where = "";
		if($startTime>0 && $endTime>0){
			$where .= " and ui.reg_time>$startTime and ui.reg_time<$endTime";
		}

		//count(ui.user_id) as count,
		$list=$this->select("ui.user_id,ui.ref_uid")
				  ->from('user_info ui')
				  ->leftjoin('usaccount su','ui.user_id = su.user_id')
				  ->where("ui.ref_uid>0 and ui.coin_status=0 and su.ac_type in(2,3) and su.n_in>0".$where)
				  ->order('ui.ref_uid desc')
				  //->group('ui.ref_uid')
				  ->getAll();

		return $list;
	}
	
	/*
	 * 获取从2014-08-22 17:00:00开始注册的用户
	 * 每天批处理需要用到
	 * @access public
     * @return array
	*/
	public function getBynowUser(){
		//获取释怀通过推荐而来且通过了银行认证的数据列表

		$list=$this->select("ui.user_id")
				  ->from('user_info ui')
				  ->where("ui.reg_time>".strtotime('2014-08-22 17:00:00')." and ui.coin_status=0")
				  ->order('ui.user_id desc')
				  //->group('ui.ref_uid')
				  ->getAll();

		return $list;
	}
	
	/**
	 * 获取某段时间注册的用户
	 */
	public function getUser($startTime,$endTime){
		$list=$this->select("ui.user_id,ui.ref_uid,ui.reg_time")
				  ->from('user_info ui')
				  ->where("ui.reg_time>$startTime and ui.reg_time<$endTime and ui.ref_uid>0")
				  ->order('user_id desc')
				  ->getAll();

		return $list;
	} 
	
	/**
	 * 检查交易密码是否正确
	 * @param  int $user_id   用户id
	 * @param  string $paypasswd 要检查的交易密码
	 * @return bool
	 */
	public function checkPaypasswd($user_id,$paypasswd){
		if(empty($paypasswd)) throw new exception('请输入交易密码！',40);
		$uaccount = M('user:userAccount')->getPk($user_id);
		if(empty($uaccount['pay_passwd'])) throw new exception('请先设置交易密码！',41);
		$npassword=M('system:sysUser')->genPassword($paypasswd.$uaccount['salt']);
		if($uaccount['pay_passwd']!=$npassword){
			throw new exception('对不起，交易密码输入不正确！',42);
		}
		return true;
	}

	/**
	 * 添加用户的佣金到用户佣金表
	 * @param  int $user_id 用户id
	 * @return array
	 */
	public function addUserCommission($data){
		return $this->model('user_commission')->add($data);
	}
}
?>