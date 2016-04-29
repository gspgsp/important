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
			//qq检查
			if(strlen($param['qq'])>16){
				return array('err'=>1,'msg'=>'QQ号码长度错误');
			}
		}else{    //开始验证添加的企业（公司名字)
			if(empty($param['info_mobile']) || empty($param['info_email']) || ){

			}
			if(!empty($param['c_name'])){
				if(!M('user:customer')->curUnique('c_name',$param['c_name'])){
					return array('err'=>1,'msg'=>'公司名称已经存在');
				}
			}
			//chk营业执照
			if(!empty($param['business_licence'])){
				if(!M('user:customer')->curUnique('business_licence',$param['business_licence'])){
					return array('err'=>1,'msg'=>'公司营业执照号码已经存在');
				}
			}
			//chk税务
			if(!empty($param['tax_registration'])){
				if(!M('user:customer')->curUnique('tax_registration',$param['tax_registration'])){
					return array('err'=>1,'msg'=>'税务代码已经存在');
				}
			}
			//chk组织代码
			if(!empty($param['organization_code'])){
				if(!M('user:customer')->curUnique('organization_code',$param['organization_code'])){
					return array('err'=>1,'msg'=>'公司组织代码已经存在');
				}
			}
			//chk法人身份证
			if(!empty($param['legal_idcard'])){
				if(strlen($param['legal_idcard'])<15){
					return array('err'=>1,'msg'=>'身份证号码不正确');
				}
				if(!M('user:customer')->curUnique('legal_idcard',$param['legal_idcard'])){
					return array('err'=>1,'msg'=>'法人身份证号码已存在');
				}
			}
			//电话
			if(!empty($param['info_mobile'])){
				if(!is_mobile($param['info_mobile'])){
					return array('err'=>1,'msg'=>'您的手机号码格式不正确');
				}	
				if(!$this->usrUnique('mobile',$param['info_mobile'])){
					return array('err'=>1,'msg'=>'手机号已存在');
				}
			}
			//email检查
			if(!empty($param['info_email'])){
				if(!is_email($param['info_email'])){
					return array('err'=>1,'msg'=>'email格式不正确');
				}	
				if(!$this->usrUnique('email',$param['info_email'])){
					return array('err'=>1,'msg'=>'email已存在');
				}
			}
			//qq检查
			if(!empty($param['info_qq'])){
				if(strlen($param['info_qq'])>16) return  array('err'=>1,'msg'=>'QQ号码长度错误');
				if(!$this->usrUnique('qq',$param['info_qq'])){
					return array('err'=>1,'msg'=>'qq号码已存在');
				}
			}
		}
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'depart'=>$_SESSION['depart'],
			'customer_manager'=>$_SESSION['adminid']
		);
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

		); 
		// 开始添加数据
		$result = $info['ctype']==1 ? $this->model('customer_contact')->add($info+$_data) : ($this->model('customer')->add($info+$_data) && $this->model('customer_contact')->add($info_ext+$_data));
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

	//更新用户联系人或者是公司
	public function customerUpdate($para=array(),$info=array(),$user_id=0){
		if($user_id){
			return false;
		}
				if($info['ctype']==1){
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

			//qq检查
			if(strlen($param['qq'])>16){
				return array('err'=>1,'msg'=>'QQ号码长度错误');
			}
		}else{		//开始验证添加的企业（公司名字)
			if(empty($param['info_mobile']) || empty($param['info_email']) || ){

			}
			if(!empty($param['c_name'])){
				if(!M('user:customer')->curUnique('c_name',$param['c_name'])){
					return array('err'=>1,'msg'=>'公司名称已经存在');
				}
			}
			//chk营业执照
			if(!empty($param['business_licence'])){
				if(!M('user:customer')->curUnique('business_licence',$param['business_licence'])){
					return array('err'=>1,'msg'=>'公司营业执照号码已经存在');
				}
			}
			//chk税务
			if(!empty($param['tax_registration'])){
				if(!M('user:customer')->curUnique('tax_registration',$param['tax_registration'])){
					return array('err'=>1,'msg'=>'税务代码已经存在');
				}
			}
			//chk组织代码
			if(!empty($param['organization_code'])){
				if(!M('user:customer')->curUnique('organization_code',$param['organization_code'])){
					return array('err'=>1,'msg'=>'公司组织代码已经存在');
				}
			}
			//chk法人身份证
			if(!empty($param['legal_idcard'])){
				if(strlen($param['legal_idcard'])<15){
					return array('err'=>1,'msg'=>'身份证号码不正确');
				}
				if(!M('user:customer')->curUnique('legal_idcard',$param['legal_idcard'])){
					return array('err'=>1,'msg'=>'法人身份证号码已存在');
				}
			}
			//电话
			if(!empty($param['info_mobile'])){
				if(!is_mobile($param['info_mobile'])){
					return array('err'=>1,'msg'=>'您的手机号码格式不正确');
				}	
				if(!$this->usrUnique('mobile',$param['info_mobile'])){
					return array('err'=>1,'msg'=>'手机号已存在');
				}
			}
			//email检查
			if(!empty($param['info_email'])){
				if(!is_email($param['info_email'])){
					return array('err'=>1,'msg'=>'email格式不正确');
				}	
				if(!$this->usrUnique('email',$param['info_email'])){
					return array('err'=>1,'msg'=>'email已存在');
				}
			}
			//qq检查
			if(!empty($param['info_qq'])){
				if(strlen($param['info_qq'])>16) return  array('err'=>1,'msg'=>'QQ号码长度错误');
				if(!$this->usrUnique('qq',$param['info_qq'])){
					return array('err'=>1,'msg'=>'qq号码已存在');
				}
			}
		}	
		$_data = array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'depart'=>$_SESSION['depart'],
			'customer_manager'=>$_SESSION['adminid']
		);
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

		); 
		
		// 开始更新数据
		$result = $info['ctype']==1 ? $this->model('customer_contact')->wherePk($user_id)->update($info+$_data) : ($this->model('customer')->wherePk($user_id)->update($info+$_data) && $this->model('customer_contact')->wherePk($user_id)->update($info_ext+$_data));
		if($result>0){
			return array('err'=>0,'msg'=>'添加成功');
		}
		return array('err'=>1,'msg'=>'数据添加失败');
	}
	
}