<?php
/**
 * 用户检查-注册 
 */
class sysUserModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer_contact');
	}
	
	/*
	 * 用户注册
     * @access public
	 * @param array $param 账户信息(mobile,email,password,status)
	 * @param array $info 用户信息(utype,real_name,id_type,id_card)
     * @return array(err,msg,user)
	 */
	public function register($param=array(),$info=array(),$infoExt=array(),$user_att=array()){
		//手机检查
		if(!empty($param['mobile'])){
			if(!is_mobile($param['mobile'])){
				return array('err'=>1,'msg'=>'您的手机号码格式不正确');
			}	
			if(!$this->usrUnique('mobile',$param['mobile'])){
				return array('err'=>1,'msg'=>'手机号已存在');
			}
		}
		
		//email检查
		if(!empty($param['email'])){
			if(!is_email($param['email'])){
				return array('err'=>1,'msg'=>'email格式不正确');
			}	
			if(!$this->usrUnique('email',$param['email'])){
				return array('err'=>1,'msg'=>'email已存在');
			}
		}
		
		//密码检查
		if(strlen($param['password'])<8){
			return array('err'=>1,'msg'=>'密码长度应为(8-20位)');
		}
			
		//注册事务
		$this->startTrans();
		$user=$param;
		
		if(empty($user['salt'])){
			$user['salt']=randstr(6);
			$user['password']=$this->genPassword($param['password'].$user['salt']);
		}
		if(empty($user['last_ip'])){
			$user['last_ip']=get_ip();
			$user['last_login']=CORE_TIME;
			$user['visit_count']=1;
		}
		$result=$this->model('user')->add($user);
		if(empty($result)){
			return array('err'=>1,'msg'=>'数据操作失败:'.$this->getDBError());
		}
		$user_id=$this->getLastID(); //用户ID
		
		//插入用户信息
		$_data=array(
			'user_id'=>$user_id,
			'reg_ip'=>$user['last_ip'],
			'reg_time'=>CORE_TIME,
		);
		if(!empty($info)){ //合并用户信息
			if(isset($info['ref'])){
				$info['ref']=substr($info['ref'],0,200);
			}
			$_data=$_data+$info;	
		}
		$this->model('user_info')->add($_data);
		
		if(!empty($info['real_name']) && !empty($info['id_card'])){

			$db=M('system:sysIdCard');
			$result=$db->set($info['real_name'],$info['id_card'],$user_id)->register();

			if($result){
				//认证身份证，记录日志
				//M('user:userLog')->addLog($this->user_id,'idcard_verify');
			}else{
				//返回身份证错误信息
				$this->rollback();
				return array('err'=>1,'msg'=>$db->getError());
			}
		}
		
		//如果是筹资机构，添加公司信息到公司扩展表
		if($info['utype']==3){
			//用户扩展信息
			if(!empty($infoExt)){
				$_data=array(
					'user_id'=>$user_id,
				);
				$_data=$_data+$infoExt;
				
			}
			$this->model('user_info_extcompany')->add($_data); //用户扩展信息
		}
		
		//经纪人
		if($info['utype']==2){
			//用户扩展信息
			if(empty($infoExt) || empty($infoExt['name']) || empty($infoExt['address']) || empty($infoExt['contacts'])){
				$this->rollback();
				return array('err'=>1,'msg'=>'经纪人资料未填写完整');
			}

			$infoExt['user_id'] = $user_id;
			$_data=$_data+$infoExt;
			$this->model('user_info_extagent')->add($_data); //用户扩展信息
		}
		
		if(!empty($user_att)){
			foreach($user_att as $k=>$v){
				$values .= "($user_id,".$v['att_id'].",".$k."),";
			}
			$values = trim($values,',');
			//新增用户账户
			$this->model('user_att')->execute("insert into ".$this->ftable." (user_id,att_id,atype) values ".$values); //用户账户
		}
		
		//插入客户经理表
		$in_data = array(
				'user_id'=>$user_id, //客户ID
				'manager_id'=>0,//客户经理ID
				'sex'=>0,//性别
				'age'=>0,//年龄
				'plan_time'=>0,//计划拜访时间
				'visit_num'=>0,//'拜访次数',
				'last_visit'=>0,//最后拜访时间',
				'is_invest'=>"否",//'是否投资过',
				'mobile'=>$param['mobile'],//'手机号',
				'user_name'=>'',//'名称',
				'status'=>1,//'状态:1正常,2冻结,3关闭',
				'chanel_id'=>(int)$info['chanel_id'],//'用户渠道ID',
				'reg_time'=>CORE_TIME,//'注册时间',
				'last_login'=>CORE_TIME,//'最后登录时间',
				'visit_count'=>1,//'登录次数'
				'ref_uid'=>(int)$info['ref_uid'],	//推荐人	  
		);
		$this->model('manager_user')->add($in_data);
		
		//插入其他数据
		$_data=array(
			'user_id'=>$user_id,
		);
		$this->model('uaccount')->add($_data); //用户账户
		
		//新增用户账户
		$this->model('usaccount')->execute("insert into ".$this->ftable." (user_id,ac_type) values ($user_id,1),($user_id,2),($user_id,4)"); //用户账户
		
		if($this->commit()){
			if($info['ref_uid']>0){ //更新推荐用户数量
				$this->model('user_info')->where('user_id='.$info['ref_uid'])->update(array('ref_count'=>"+=1"));
				//同时更新CRM用户表中推荐用户数量
				$this->model('manager_user')->where('user_id='.$info['ref_uid'])->update(array('ref_count'=>"+=1"));
			}	
					
			if($info['chanel_id']>0){ //渠道推荐+1
				$chanel = $this->model('chanel')->getPk($info['chanel_id']);
				if($chanel){
					$this->model('chanel')->where('chanel_id='.$info['chanel_id'])->update(array('rec_count'=>"+=1"));
				}
			}
			
			//发5000体验金有效期90天
			//M('user:investExp')->recharge($user_id,5000,90);
			
			$user['user_id']=$user_id;
			//如果是个人用户发送红包
			if($info['utype'] == 1){
				try {
					$sys = M('system:setting')->getSetting();
					//发放红包给新注册用户
					if($sys['coins_bid']) M('user:userCoins')->sendCoinByRule($sys['coins_bid'],$user_id);
					//如果有推荐人，给推荐人送红包
					if($info['ref_uid'] && $sys['coins_rid']){
						M('user:userCoins')->sendCoinByRule($sys['coins_rid'],$info['ref_uid']);
					}					
				} catch (Exception $e) {
					
				}
			}
			return array('err'=>0,'msg'=>'注册成功','user'=>$user);
		}else{
			$this->rollback();
			return array('err'=>1,'msg'=>'数据操作失败:'.$this->getDbError());
		}
	}
	
	public function updateUserInfo($param=array(),$info=array(),$infoExt=array(),$user_id=0){
		
		//email检查
		if(!empty($param['email'])){
			if(!is_email($param['email'])){
				return array('err'=>1,'msg'=>'email格式不正确');
			}	
			if(!$this->usrUnique('email',$param['email'],$user_id)){
				return array('err'=>1,'msg'=>'email已存在');
			}
		}
		
		//密码检查
		if(!empty($param['password']) && strlen($param['password'])<8){
			return array('err'=>1,'msg'=>'密码长度应为(8-20位)');
		}
		
		$user=$param;
		//如果密码不为空，修改密码，否则保留原密码
		if(!empty($param['password'])){
			$user['salt']=randstr(6);
			$user['password']=$this->genPassword($param['password'].$user['salt']);
		}
		//$user['last_login']=CORE_TIME;
		$result=$this->model('user')->where('user_id='.intval($user_id))->update($user);
		if(empty($result)){
			return array('err'=>1,'msg'=>'数据操作失败:'.$this->getError());
		}
		
		//更新用户信息
		if(!empty($info)){
			if(!empty($info['real_name']) && !empty($info['id_card'])){//如果用户名身份证都填写了，直接身份验证通过
				$db=M('system:sysIdCard');
				$result=$db->set($info['real_name'],$info['id_card'],$user_id)->register();
				if(!$result){
					//返回身份证错误信息
					return array('err'=>1,'msg'=>$db->getError());
				}
			}else{
				$result=$this->model('user_info')->where('user_id='.intval($user_id))->update($info);
				if(empty($result)){
					return array('err'=>1,'msg'=>'数据操作失败:'.$this->getError());
				}
			}
	
		}
		
		//如果是筹资机构，添加公司信息到公司扩展表
		if($info['utype']==3){
			//用户扩展信息
			if(!empty($infoExt)){
				$_data=array(
					'user_id'=>$user_id,
				);
				$_data=$_data+$infoExt;
				
			}

			$result=$this->model('user_info_extcompany')->where('user_id='.intval($user_id))->update($_data);//用户扩展信息
			if(empty($result)){
				return array('err'=>1,'msg'=>'数据操作失败:'.$this->getError());
			}
		}
		
		//经纪人
		if($info['utype']==2){
			//用户扩展信息
			if(empty($infoExt) || empty($infoExt['name']) || empty($infoExt['address']) || empty($infoExt['contacts'])){
				return array('err'=>1,'msg'=>'经纪人资料未填写完整');
			}

			$infoExt['user_id'] = $user_id;
			$_data=$_data+$infoExt;
			$result = $this->model('user_info_extagent')->where('user_id='.intval($user_id))->update($_data);//用户扩展信息
			if(empty($result)){
				return array('err'=>1,'msg'=>'数据操作失败:'.$this->getError());
			}
		}
		
	}

	/*
	 * 生成加密密码
	 * @param string $password 明文密码
     * @return string 密文密码
	 */
	public function genPassword($string=''){
		return md5('rs.'.$string.'.sr');
	}
	
	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
     * @return bool（true唯一）
	 */
	public function usrUnique($name='mobile',$value='',$user_id=0){
		$where = "$name='$value'";
		if($user_id){
			$where .= " and user_id !='$user_id'";
		}
		$exist=$this->model('customer_contact')->select('user_id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}





	


	

	
}
?>
