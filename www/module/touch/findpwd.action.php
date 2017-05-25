<?php
/**
*找回密码(密码重置)
*/
class findpwdAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('customer_contact');
    }
    public function init()
	{
		$this->display('findpwd');
	}
	//ajax密码重置
	public function findpwd(){
        if($_POST){
            $this->is_ajax=true;
            $data = saddslashes($_POST);
            $resVcode=M('system:sysSMS')->chkDynamicCode($data['username'],$data['vcode']);
            if($resVcode['err']>0){
                $this->error($resVcode['msg']);
            }
            $result = M('touch:register')->findpwd(array('mobile'=>$data['username'],'password'=>$data['password']));
            if($result['err']){
                $this->error($result['msg']);
            }else{
                $this->success('密码重置成功');
            }
        }
	}
	//ajax获取短信验证码
    public function sendmsg(){
        $this->is_ajax=true;
        //验证手机和密码
        $mobile=sget('mobile','s');
        $password=sget('password','s');
        $result = M('touch:register')->isLegal(array('mobile'=>$mobile,'password'=>$password));
        if($result['err'])
            $this->error($result['msg']);
        //通过mobile获取用户的id
        if(!$u_id = $this->db->select('user_id')->where('mobile='.$mobile)->getOne()) $this->error(array('err'=>1,'msg'=>'没有该用户'));
        //系统短信模型
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
        $sms->send($u_id,$mobile,$msg,2);
        $this->success('发送成功');
    }
}