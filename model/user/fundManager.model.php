<?php

/**
*资金管理模型
*/
class fundManagerModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer');
	}
	//营业执照号验证
	public function chkIsLegal($data){
		//验证姓名
		if(!is_chinese($data['legal_person'])){
			//排除一些特殊字符：校验中文
			if(!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,25}$/', str_replace('·','',$data['legal_person'])))
				return array('err'=>1,'msg'=>'请输入正确的中文姓名');
		}
		if(strlen($data['legal_person'])<2 || strlen($data['legal_person'])>30)return array('err'=>1,'msg'=>'请输入正确的中文姓名');

		//验证身份证合法性
		if(!$this->is_idcard($data['legal_idcard']))return array('err'=>1,'msg'=>'身份证号不合法');

		//验证营业执照号合法性
		if(!preg_match('/^(\s)*(\d{15})(\s)*$/', $data['business_licence'])) return array('err'=>1,'msg'=>'营业执照号不合法');

		//写入数据库
		$data['organization_state'] = 2;
		if(!$this->model('customer')->where('c_id='.$_SESSION['uinfo']['c_id'])->update($data)) return array('err'=>1,'msg'=>'更新失败');
		return array('err'=>0,'msg'=>'验证通过,更新成功');
	}
	/**
	 * 判断身份证号格式是否正确
	 * @return bool
	 */
	private function is_idcard($idcard) {
		return  preg_match('/^(\s)*(\d{15}|\d{18}|\d{17}x)(\s)*$/i', $idcard);
	}
}