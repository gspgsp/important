<?php
/**
 * 修改密码--黎贤
 */
class changepassAction extends userBaseAction{


	public function init()
	{
		$this->act='changepass';
		if($_POST){
			$this->is_ajax=true;
			$old=trim(sget('old','s',''));
			$new=trim(sget('new','s',''));
			$renew=trim(sget('renew','s',''));
			if($old==''||$new==''||$renew=='') $this->error('请输入密码');
			if( strlen($old)<6||strlen($old)>16 ) $this->error('密码长度为6-16位字符');
			if( strlen($new)<6||strlen($new)>16 ) $this->error('密码长度为6-16位字符');
			if( $new!=$renew ) $this->error('两次密码不一致');
			$model=new customerContactModel();
			$uinfo=$model->where("user_id=$this->user_id")->getRow();
			if(empty($uinfo)) $this->error('错误的账号');
			$npassword=M('system:sysUser')->genPassword($old.$uinfo['salt']);
			if($uinfo['password']!==$npassword){
				$this->error('错误的密码');
			}
			$newpass=M('system:sysUser')->genPassword($new.$uinfo['salt']);
			$_data=array(
				'password'=>$newpass,
				'update_time'=>CORE_TIME,
			);
			if(!$model->where("user_id=$this->user_id")->update($_data)) $this->error('操作失败，系统错误');
			$this->success('操作成功');

		}else{
			$this->display('changepass');
		}
		
	}
}