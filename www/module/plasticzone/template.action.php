<?php
/**
*临时注册通道-gsp
*/
class templateAction extends homeBaseAction
{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{
		$this->display('me_reg');
	}
	public function register()
	{
		$this->is_ajax = true;
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)) $this->error($this->err);
		$password=sget('password','s');
		if(!$this->_chkpass($password)) $this->error($this->err);
		$mcode=spost('code','s');
		$result=M('system:sysSMS')->chkDynamicCode($mobile,$mcode);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$user_model=M('system:sysUser');
		$salt=randstr(6);
		$passwordSalt = $user_model->genPassword($password.$salt);
		$_SESSION['check_reg_ok']=true;
		$_SESSION['mobile']=$mobile;
		$_SESSION['password']=$passwordSalt;
		$_SESSION['salt']=$salt;
		$this->success('注册成功');

	}
	//进入补全信息页
	public function enReginfo()
	{
		$this->display('me_completeinfo');
	}
	//信息补全页面
	public function reginfo()
	{
		if(!$_SESSION['check_reg_ok']) $this->forward('/template/register');
		if($_POST)
		{
			$this->is_ajax=true;
			if(!sget('name','s')) $this->error('请输入姓名');
			// if(!sget('qq','s')) $this->error('请输入qq号码');
			$c_name=sget('c_name','s');
			$region =sget('region','s','');
			if(!$c_name) $this->error('请输入公司名称');
			$cus_model=$this->db->model('customer');
			$customer=$cus_model->select('c_id')->where("c_name='$c_name'")->getOne();
			$user_model=M('system:sysUser');
			$user_model->startTrans();
			try {
				$c_id=$customer;
				if(!$customer){
					$_customer=array(
						'c_name'=>$c_name,
						'chanel'=>sget('chanel','i',0),
						'input_time'=>CORE_TIME,
						'customer_manager'=>859,//交易员
					);
					if(!$cus_model->add($_customer)) throw new Exception("系统错误 reg:101");
					$c_id=$cus_model->getLastID();
				}
				//查看是否为老用户
				$old_user = $this->db->model('customer_contact')->select('user_id,parent_mobile')->where("mobile=".$_SESSION['mobile'])->getRow();
				if($old_user['user_id']){
					$parent_mobile = sget('parent_mobile','s');
					$_user=array(
							'salt'=>$_SESSION['salt'],
							'password'=>$_SESSION['password'],
							'name'=>sget('name','s'),
							'qq'=>sget('qq','s'),
							'parent_mobile'=>empty($old_user['parent_mobile'])?$parent_mobile:$old_user['parent_mobile'],
							'c_id'=>$c_id,
							'sex'=>sget('sex','i',0),
							'chanel'=>sget('chanel','i',0),
							'update_time'=>CORE_TIME,
						);
					if(!$user_model->where("mobile=".$_SESSION['mobile'])->update($_user)) throw new Exception("系统错误 reg:105");
				}else{
					$is_default=empty($customer)?1:0;
					$_user=array(
						'mobile'=>$_SESSION['mobile'],
						'salt'=>$_SESSION['salt'],
						'password'=>$_SESSION['password'],
						'name'=>sget('name','s'),
						'qq'=>sget('qq','s'),
						'c_id'=>$c_id,
						'sex'=>sget('sex','i',0),
						'customer_manager'=>859,//交易员
						'input_time'=>CORE_TIME,
						'is_default'=>$is_default,
						'parent_mobile'=>sget('parent_mobile','s'),
						'chanel'=>sget('chanel','i',0),
					);
					if(!$user_model->add($_user)) throw new Exception("系统错误 reg:102");
					$user_id=$user_model->getLastID();
					//直接关注
					if(!empty($_user['parent_mobile'])){
						$focused_id = $this->db->model('customer_contact')->where("mobile=".$_user['parent_mobile'])->select('user_id')->getOne();
						M('plasticzone:plasticAttention')->getAttention($user_id,$focused_id);
						//如果有引荐人，则加积分
						$billModel=M('points:pointsBill');
						$refS = intval($this->plastic_points['ref']);
						$billModel->addPoints($refS, $focused_id, 12, 1);
						//
					}
					//手机号地址
					$mobile_area = M('plasticzone:plasticPersonalInfo')->getMobileAddress($_SESSION['mobile']);
					//
					$_info=array(
						'user_id'=>$user_id,
						'reg_ip'=>get_ip(),
						'reg_time'=>CORE_TIME,
						'thumbcard'=>$_SESSION[$_SESSION['mobile'].'_CardImg'],
						'reg_chanel'=>sget('chanel','i',0),
						'mobile_province'=>empty($mobile_area)?'':$mobile_area['province'],
						'mobile_area'=>empty($mobile_area)?'':$mobile_area['city'],
					    'region'=>empty($region)?'':$region,
					);
					if(!$this->db->model('contact_info')->add($_info)) throw new Exception("系统错误 reg:103");
					if(!$customer){
						if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=".$user_id)) throw new Exception("系统错误 reg:104");
					}
					//新增用户默认排序最前
					$this->db->model('weixin_ranking')->add(array('user_id'=>$user_id,'pm'=>0,'rownum'=>0,));
				}
				//
			} catch (Exception $e) {
				$user_model->rollback();
				$this->error($e->getMessage());
			}
			$user_model->commit();
			$_SESSION['mobile']=null;
			$_SESSION['password']=null;
			$_SESSION['salt']=null;
			$_SESSION['check_reg_ok']=null;
			$this->success('完善成功');//完善成功后跳转到个人中心
		}
	}
	/**
     * 验证手机号码
     * @access private
     * @return bool
     */
	private function _chkmobile($value=''){
		if(!is_mobile($value)){
			if(empty($value)){
				$this->err='请输入手机号码';
			}else{
				$this->err='错误的手机号码';
			}
			return false;
		}
		return true;
	}
	/**
	 * 验证密码
	 */
	private function _chkpass($pass,$repass){
		if(strlen($pass)<6){
			$this->err='密码格式不正确,至少6位';
			return false;
		}
		return true;
	}
	/**
     * 发送手机验证码
     * @access public
     * @return html
     */
	public function sendmsg(){
		$this->is_ajax=true;
		//验证手机
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)){
			$this->error($this->err);
		}
		$sms=M('system:sysSMS');
		//检查注册的限制
		$result=$sms->chkRegLimit($mobile,get_ip());
		if(empty($result)){
			$this->error($sms->getError());
		}
		//请求动态码
		$result=$sms->genDynamicCode($mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		$msg=$result['msg']; //短信内容
		//发送手机动态码
		$sms->send(0,$mobile,$msg,2);
		$this->success('发送成功');
	}
}