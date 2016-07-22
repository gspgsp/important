<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/22
 * Time: 9:20
 */

    class mdownloadAction extends homeBaseAction {

        protected $err,$reg_vcode;
        public function __init(){
            $this->reg_vcode = $this->sys['security']['reg']['vcode'];
        }
        public function mdownload(){


            $this->display('mdownload');
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
            $chk=M('system:sysUser')->usrUnique('mobile',$value);//非重复
            if($chk){
                $this->err='账号不存在';
                return false;
            }else{
                return true;
            }
        }

        /**
         * 发送手机验证码
         * @access public
         * @return html
         */
        public function sendmsg(){
            $this->is_ajax=true;
            //验证手机，邮箱，验证码
            $mobile=sget('mobile','s');
            if(!$this->_chkmobile($mobile)){$this->error($this->err);}
            if($this->reg_vcode){
                $vcode=strtolower(sget('regcode','s'));
                if(empty($vcode)){
                    $this->error('请输入验证码');
                }
                //检查验证码
                if(!chkVcode('regcode',$vcode)){
                    $this->error('验证码不正确，请重新输入');
                }

            }
            $sms=M('system:sysSMS');

            //请求动态码
            $result=$sms->getPush($mobile);
//            $result=$sms->genDynamicCode($mobile);
            if($result['err']>0){ //请求错误
                $this->error($result['msg']);
            }

            $msg=$result['msg']; //短信内容
            //发送手机动态码
            $sms->send(0,$mobile,$msg,8);
            $this->success('发送成功');
        }
    }