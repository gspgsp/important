<?php
/*
 * 后台主界面
*/
class indexAction extends adminBaseAction {
	public function __init(){
		$this->debug=false;
	}

    /**
     * 后台主界面
     * @access public
     */
	public function init(){
		$this->assign('frameTab', true);//是否使用frame标签
		
		$menu=M('rbac:rbac')->_getMyMenu();
		$this->assign('menu', $menu);
		
		$this->assign('page_title','管理系统');
		$this->display('index');
    }
	
    /**
     * 启始页
     * @access public
     */
	public function main(){
		//系统信息
		$sys_info['操作系统']     = PHP_OS;
		$sys_info['IP']          = $_SERVER['SERVER_ADDR'];
		$sys_info['Web服务器']    = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['PHP版本']      = PHP_VERSION;
		$sys_info['MySQL版本']    = $this->db->getVersion();
		$sys_info['Zlib支持']     = function_exists('gzclose') ? '是':'否';
		$sys_info['安全模式']      = (boolean) ini_get('safe_mode') ?  '是':'否';
		$sys_info['安全模式GID']   = (boolean) ini_get('safe_mode_gid') ? '是' : '否';
		$sys_info['时区']         = function_exists("date_default_timezone_get") ? date_default_timezone_get() : '无需设置';
		$sys_info['Socket支持']   = function_exists('fsockopen') ? '是' : '否';
		
		$gd=gd_version();
		if ($gd == 0){
			$sys_info['gd'] = 'N/A';
		}else{
			if ($gd == 1){
				$sys_info['gd'] = 'GD1';
			}else{
				$sys_info['gd'] = 'GD2';
			}
			$sys_info['gd'] .= ' (';
			//检查系统支持的图片类型 
			if ($gd && (imagetypes() & IMG_JPG) > 0){
				$sys_info['gd'] .= ' JPEG';
			}
			if ($gd && (imagetypes() & IMG_GIF) > 0){
				$sys_info['gd'] .= ' GIF';
			}
			if ($gd && (imagetypes() & IMG_PNG) > 0){
				$sys_info['gd'] .= ' PNG';
			}	
			$sys_info['gd'] .= ')';
		}
		
		//允许上传的最大文件大小
		$sys_info['上传附件限制'] = ini_get('upload_max_filesize');
		$sys_info['执行时间限制'] = ini_get('max_execution_time').'秒';
		$sys_info['服务器时间'] = date("Y-m-d H:i:s");
		$this->assign('sys_info', $sys_info);

		$this->assign('page_title','服务器信息');
		$this->display('main');
	}
	
    /**
     * 修改管理员密码
     * @access public
     */
	public function modifyPasswd(){
		
		$data = sdata(); //传递的参数
		if(!empty($data)){
			$this->is_ajax=true; //指定为Ajax输出
			if(strlen($data['oldpassword'])<6 || strlen($data['newpassword'])<6){
				$this->error('错误的密码信息');
			}

			$user=$this->db->model('admin')->select('admin_id,username,password')->where("admin_id=".$this->admin_id)->getRow();
			if(md5($data['oldpassword'])!=$user['password']){
				$this->error('原密码不正确');
			}
			$_data=array(
				'password'=>md5($data['newpassword']),		 
			);
			$this->db->model('admin')->wherePk($this->admin_id)->update($_data);
			$GLOBALS['CORE_SESS']->destroy();
			cookie::set('admincy', '');
			$this->success('密码更新成功，请重新登录');
		}
		
		$this->assign('page_title','管理员角色管理');
		$this->display('passwd.html');
	}
}
?>