<?php
/**
*短信模型-app
*/
class msgModel extends model
{
	private $channel = 0;
	public function __construct() {
		parent::__construct(C('db_default'), 'log_sms');
	}
	//生成短信验证码
	public function genDynamicCode($mobile,$stype=0){
		$mcode=0;
		$msg = $this->model('log_sms')->where("mobile='{$mobile}' and stype=$stype")->order('input_time desc')->limit('0,1')->getRow();
		if(empty($msg)||(CORE_TIME-$msg['input_time']>300)){
			$mcode=mt_rand(100820,999560);
			$msg=sprintf(L('sms_template.dynamic_code'),$mcode);
		}else{
			if(CORE_TIME-$msg['input_time']<60){//60秒内不能重复请求
				return array('err'=>1,'msg'=>'动态码已发送成功，请稍候再试');
			}
			if(CORE_TIME-$msg['input_time']<=300){//有效期300秒
				$mcode=mb_substr($msg['msg'],4,6);
				$msg=sprintf(L('sms_template.dynamic_code'),$mcode);
			}
		}
		return array('err'=>0,'msg'=>$msg);
	}
	/*
	 * 发送短信
     * @access public
	 * @param int $user_id 用户ID
	 * @param string $mobile 手机号
	 * @param string $msg 短信内容
	 * @param int $stype 类型:1注册2更新密码3支付密码4交易类,8提醒类,10促销类
	 * @param int $input_time 发送时间
     * @return bool
	 */
	public function send($user_id=0,$mobile='',$msg='',$stype=1,$input_time=0){

		if(!$input_time){
			//提醒类短信发送时间限制
			$input_time=CORE_TIME;
			if($stype==8){
				if(date('G') > 21){
					$input_time = strtotime('tomorrow 09:10:00');
				}elseif(date('G') < 9){
					$input_time = strtotime('today 09:10:00');
				}
			}
		}

		$_data=array(
			'user_id'=>(int)$user_id,
			'mobile'=>$mobile,
			'msg'=>addslashes($msg),
			'stype'=>(int)$stype,
			'input_time'=>$input_time,
			'user_ip'=>get_ip(),
			'chanel'=>$this->channel
		);

		return $this->model('log_sms')->add($_data);
	}
	/*
	 * 检查动态码
     * @access public
	 * @param string $mobile 手机号
     * @return (err,msg)
	 */
	public function chkDynamicCode($mobile='',$mcode='',$stype=0){
		$msg = $this->model('log_sms')->where("mobile='{$mobile}' and stype=$stype")->order('input_time desc')->limit('0,1')->getRow();
		if(count($msg)>0){
			if(CORE_TIME-$msg['input_time']>300){
				return array('err'=>1,'msg'=>'手机动态码已失效');
			}else if($mcode==mb_substr($msg['msg'],4,6)){
				return array('err'=>0,'msg'=>'验证通过');
			}

		}else
		{
		    return array('err'=>2,'msg'=>'手机动态码输入不正确，请重新输入');
		}

	}

}