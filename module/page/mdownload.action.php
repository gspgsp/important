<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/22
 * Time: 9:20
 */

	class mdownloadAction extends homeBaseAction{
		protected $err,$reg_vcode;
		public function __init(){
			$this->reg_vcode = $this->sys['security']['reg']['vcode'];
		}
		/**
		 * 展示下载页面
		 * @Author   cuiyinming
		 * @DateTime 2016-07-22T17:52:09+0800
		 * @return   [type]                   [description]
		 */
		public function mdownload(){
			$this->display('mdownload');
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
			if(!is_mobile($mobile)) $this->error('手机号码格式错误');
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
			//请求动态码
			if(isset($mobile)&& !empty($mobile)){
				$str=shortenURL('http://statics.myplas.com/myapp/app/myplas.apk');
				$msg=sprintf(L('sms_push.push_code'),$str);
				//放送下载链接
				$result  = M('system:sysSMS')->send($this->user_id,$mobile,$msg,8);
				if($result['err']>0){ //请求错误
					$this->error($result['msg']);
				}else{
					$this->success('发送成功！');
				}		
			}else{
				$this->error('发送错误');
			}
		}
		/**
		 * 404错误页面
		 */
		public function wrong(){
			$this->display('404.html');
		}
	}