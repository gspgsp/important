<?php
/**
* 临时登录通道-gsp
*/
class loginAction extends homeBaseAction
{
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{
		$this->display('me_login');
	}
	public function login()
	{
		if($_POST){
			$this->is_ajax=true;
			$username=sget('username','s');
			$password=sget('password','s');
			if(strlen($username)<10 || !is_mobile($username)){
				$this->error('手机号错误');
			}elseif(strlen($password)<6){
				$this->error('密码错误');
			}
			$uinfo = $this->db->model('customer_contact')->where('mobile='.$username)->getRow();
			//判断是否为老用户
			if($uinfo['chanel']!=6) $this->json_output(array('err'=>6,'msg'=>'请注册'));
			//判断账号是否锁定
			if($uinfo['login_unlock_time'] > CORE_TIME){
				$this->json_output(array('err'=>3,'msg'=>'您的账号已被锁定，请稍候再试'));
			}
			$npassword=M('system:sysUser')->genPassword($password.$uinfo['salt']);
			if($uinfo['password']!==$npassword){
				$_data = array();
				$_data['login_fail_count'] = $uinfo['login_fail_count'] >= 10 ? 1 : $uinfo['login_fail_count']+1;
				$msg = '密码不正确，连续输错10次将被锁定';

				if($_data['login_fail_count'] == 10){
					$_data['login_unlock_time'] = CORE_TIME + 14400;
					$msg = '已输错10次密码，账号将锁定4小时';
				}
				$this->db->model('customer_contact')->where("user_id={$uinfo['user_id']}")->update($_data);
				$this->json_output(array('err'=>4,'msg'=>$msg));
			}
			//状态:1正常,2冻结,3关闭
			if(in_array($uinfo['status'],array(2,3))){
				$this->json_output(array('err'=>5,'msg'=>'您的帐号已被冻结，请联系客服400-6129-965
'));
			}
			//登录次数
			$today = strtotime(date('Y-m-d',time()));
			$login_count = $this->db->model('log_login')->where("user_id={$uinfo['user_id']} and chanel=6 and input_time > $today")->select('count(id)')->getOne();
			//开启事物
			$billModel=M('points:pointsBill');
			$passport = M('user:passport');
			$passport->startTrans();
			try{
				if($login_count < 1) {
					$loginS = intval($this->plastic_points['login']);
					if(!$billModel->addPoints($loginS, $uinfo['user_id'], 2, 1)) throw new Exception('系统错误。code:202');
				}
				if(!$passport->setSession($uinfo['user_id'],$uinfo)) throw new Exception('系统错误。code:203');
				if(!$passport->loginSuccess($uinfo['user_id'],$chanel=6)) throw new Exception('系统错误。code:204');
			}catch(Exception $e){
				$passport->rollback();
				$this->json_output(array('err'=>7,'msg'=>$e->getMessage()));
			}
			$passport->commit();
            $this->json_output(array('err'=>0,'msg'=>'登录成功','user_id'=>$uinfo['user_id'],'mobile'=>$username));
		}
	}
}