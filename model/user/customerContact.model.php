<?php
/**
 * 客户联系人信息表
 */
class customerContactModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer_contact');
	}

	//添加用户联系人或者是公司
	public function customerAdd($param=array(),$info=array()){
		if($info['ctype']==1){
			$uid = $info['user_id']>0 ? $info['user_id'] : 0;
			if(!empty($param['mobile'])){
				if(!is_mobile($param['mobile'])){
					return array('err'=>1,'msg'=>'您的手机号码格式不正确');
				}
				if(!$this->usrUnique('mobile',$param['mobile'],$uid)){
					return array('err'=>1,'msg'=>'手机号已存在');
				}
			}
			//email检查
			if(!empty($param['email'])){
				if(!is_email($param['email'])){
					return array('err'=>1,'msg'=>'email格式不正确');
				}	
				if(!$this->usrUnique('email',$param['email'],$uid)){
					return array('err'=>1,'msg'=>'email已存在');
				}
			}
			//qq检查
			if(!empty($param['qq'])){
				if(strlen($param['qq'])>16) return  array('err'=>1,'msg'=>'QQ号码长度错误');
				if(!$this->usrUnique('qq',$param['qq'],$uid)){
					return array('err'=>1,'msg'=>'qq号码已存在');
				}
			}
		}else{    
			$uid = $info['info_user_id']>0 ? $info['info_user_id'] : 0;
			$cid = $info['c_id']>0 ? $info['c_id'] : 0;
			//开始验证添加的企业（公司名字)
			if(empty($param['info_mobile'])){
				return array('err'=>1,'msg'=>'联系人电话不能为空');
			}
			if(!empty($param['c_name'])){
				if(!M('user:customer')->curUnique('c_name',$param['c_name'],$cid)){
					return array('err'=>1,'msg'=>'公司名称已经存在');
				}
			}
			//chk营业执照
			if(!empty($param['business_licence'])){
				if(!M('user:customer')->curUnique('business_licence',$param['business_licence'],$cid)){
					return array('err'=>1,'msg'=>'公司营业执照号码已经存在');
				}
			}
			//chk税务
			if(!empty($param['tax_registration'])){
				if(!M('user:customer')->curUnique('tax_registration',$param['tax_registration'],$cid)){
					return array('err'=>1,'msg'=>'税务代码已经存在');
				}
			}
			//chk组织代码
			if(!empty($param['organization_code'])){
				if(!M('user:customer')->curUnique('organization_code',$param['organization_code'],$cid)){
					return array('err'=>1,'msg'=>'公司组织代码已经存在');
				}
			}
			//chk法人身份证
			if(!empty($param['legal_idcard'])){
				if(strlen($param['legal_idcard'])<15){
					return array('err'=>1,'msg'=>'身份证号码不正确');
				}
				if(!M('user:customer')->curUnique('legal_idcard',$param['legal_idcard'],$cid)){
					return array('err'=>1,'msg'=>'法人身份证号码已存在');
				}
			}
			//电话
			if(!empty($param['info_mobile'])){
				if(!is_mobile($param['info_mobile'])){
					return array('err'=>1,'msg'=>'您的手机号码格式不正确');
				}	
				if(!$this->usrUnique('mobile',$param['info_mobile'],$uid)){
					return array('err'=>1,'msg'=>'手机号已存在');
				}
			}
			//email检查
			if(!empty($param['info_email'])){
				if(!is_email($param['info_email'])){
					return array('err'=>1,'msg'=>'email格式不正确');
				}	
				if(!$this->usrUnique('email',$param['info_email'],$uid)){
					return array('err'=>1,'msg'=>'email已存在');
				}
			}
			//qq检查
			if(!empty($param['info_qq'])){
				if(strlen($param['info_qq'])>16) return  array('err'=>1,'msg'=>'QQ号码长度错误');
				if(!$this->usrUnique('qq',$param['info_qq'],$uid)){
					return array('err'=>1,'msg'=>'qq号码已存在');
				}
			}
			// 组合区域
			$info['origin'] = $info['company_province'].'|'.$info['company_city'];
			//公司联系人信息
			$info_ext = array(
				'name'=>$info['info_name'],
				'sex'=>$info['info_sex'],
				'mobile'=>$info['info_mobile'],
				'qq'=>$info['info_qq'],
				'fax'=>$info['info_fax'],
				'tel'=>$info['info_tel'],
				'email'=>$info['info_email'],
				'remark'=>$info['info_remark'],
				'status'=>$info['info_status'],
				'is_default'=>1,
			); 
		}
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'depart'=>$_SESSION['depart'],
			'customer_manager'=>$_SESSION['adminid']
		);
		$data = array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		if($info['ctype']==1 && $info['user_id']>0){
			//更新联系人
			$result = $this->model('customer_contact')->where("user_id = ".$info['user_id'])->update($info+$data);
		}else if($info['ctype']==3 && $info['info_user_id']>0 && $info['c_id']>0){
			//更新公司信息和联系人
			$result = ($this->model('customer_contact')->where("user_id = ".$info['info_user_id'])->update($info_ext+$data) && $this->model('customer')->where("c_id = ".$info['c_id'])->update($info+$data));
		}else{
			// 添加客户和联系人
			if($info['ctype']==1){
				$result =  $this->model('customer_contact')->add($info+$_data+array('chanel'=>5,));
			}else{
				$this->startTrans();
					$this->model('customer_contact')->add($info_ext+$_data+array('chanel'=>5,));
					$contact_id = $this->getLastID();
					$this->model('customer')->add($info+$_data+array('contact_id'=>$contact_id,));
					$customer_id = $this->getLastID();
					$this->model('customer_contact')->where("user_id=$contact_id")->update(array('c_id'=>$customer_id,));
				if($this->commit()){
					return array('err'=>0,'msg'=>'添加成功');
				}else{
					$this->rollback();
					return array('err'=>1,'msg'=>'数据添加失败');
				};
			} 
		}
		
		if($result>0){
			return array('err'=>0,'msg'=>'添加成功');
		}
		return array('err'=>1,'msg'=>'数据添加失败');
		
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

	/*
	 * 根据客户ID查出联系人
	 */
	public function getListByCid($cid){
		return $this->where("c_id=$cid")->select('user_id,name')->getAll();
	}
	/*
	*根据客户的id获取下面归属的全部联系人
	 */
	public function getMyListByCid($cid){
		return $this->where("c_id=$cid")->select('user_id as id,name')->getAll();
	}
	/*
	 * 根据模糊联系人查出客户ID
	 */
	public function getCidByName($name){
		return $this->where("name like '%$name%'")->select('user_id')->getCol();
	}

	/*
	 * 根据联系人ID查出联系人信息
	 */
	public function getListByUserid($user_id){
		return $this->model('customer_contact')->where("user_id=$user_id")->getRow();
	}


	/**根据当前用户id获取当前用户相关信息
	 * @param $userid
     */
	public function getCustomerInFoById($userid){
		return $this->from('customer_contact con')
					->leftjoin('customer as cus','con.c_id=cus.c_id')
					->leftjoin('admin as adm','adm.admin_id=cus.customer_manager')
					->leftjoin('contact_info as cin','cin.user_id=con.user_id')
					->leftjoin('user_msg as msg','msg.user_id=con.user_id')
					->where("con.user_id=$userid and msg.is_read=1 and msg.utype=0")
					->select('con.last_login,con.name as u_name ,con.input_time,cus.c_name,cus.identification,adm.mobile,adm.name as adm_name,adm.pic,cin.thumb,cin.points,count(msg.id) as number')
			        ->getRow();
		}

	/**
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='name',$condition='user_id'){
		$result =  $this->select("$col")->where("$condition='$value'")->getOne();
		return empty($result) ? '-' : $result;
	}

	/**
	 * 根据user_id 获取用户关联信息信息
	 */
	public function getContactByuserid($user_id=0)
	{
		if(is_array($user_id)){
			$where="con.user_id in (".implode(',',$user_id).")";
		}else{
			$where="con.user_id={$user_id}";
		}
		$model=$this->from('customer_contact con')
			->leftjoin('customer cus','con.c_id=cus.c_id')
			->leftjoin('admin ad','cus.customer_manager=ad.admin_id')
			->leftjoin('contact_info info','con.user_id=info.user_id')
			->where($where)
			->select('con.user_id,con.name,con.mobile,cus.c_name,ad.name as adname,ad.mobile as admobile,info.points');
		if(is_array($user_id)){
			return $model->getAll();
		}else{
			return $model->getRow();
		}
	}


	public function getUserInfoByid($user_id)
	{
		return $this->from('customer_contact con')
			->join('contact_info info','con.user_id=info.user_id')
			->where("con.user_id={$user_id}")
			->getRow();
	}

	public function getInfoById($user_id){
		return $this->model('contact_info')->where("user_id=$user_id")->getRow();
	}

	/*
	 * 生成加密密码
	 * @param string $password 明文密码
	 * @return string 密文密码
	 */
	public function genPassword($string=''){
		return md5('rs.'.$string.'.sr');
	}

	/**
	 *用户修改个人信息验证规则
	 */
	public function checkEditInfo($contact=array()){
			//固定电话
			if(!empty($contact['tel'])){
				if(!_istel($contact['tel'])){
					return array('err'=>1,'msg'=>'您的固定电话格式不正确');
				}
			}
			//email检查
			if(!empty($contact['email'])){
				if(!_ismyEmail($contact['email'])){
					return array('err'=>1,'msg'=>'email格式不正确');
				}
			}
			//qq检查
			if(!empty($contact['qq'])){
				if(strlen($contact['qq'])>16) return  array('err'=>1,'msg'=>'QQ号码长度错误');
			}
	}
	//固定电话验证规则
	private function _istel($str){
	    $pattern = "/^(0(10|21|22|23|[1-9]{3})(-|))?[1-9]{7,8}$/";
		return preg_match($pattern,$str);
	}
	//邮箱验证规则
	private function _ismyEmail($str){
		return strlen($email) > 6 && preg_match("/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/", $email);
	}

}