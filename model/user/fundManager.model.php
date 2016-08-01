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
// 		//验证姓名
// 		if(!is_chinese($data['legal_person'])){
// 			//排除一些特殊字符：校验中文
// 			if(!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,25}$/', str_replace('·','',$data['legal_person'])))
// 				return array('err'=>1,'msg'=>'请输入正确的中文姓名');
// 		}
// 		if(strlen($data['legal_person'])<2 || strlen($data['legal_person'])>30)return array('err'=>1,'msg'=>'请输入正确的中文姓名');

// 		//验证身份证合法性
// 		if(!$this->is_idcard($data['legal_idcard']))return array('err'=>1,'msg'=>'身份证号不合法');

// 		//验证营业执照号合法性
// 		if(!preg_match('/^(\s)*(\d{15})(\s)*$/', $data['business_licence'])) return array('err'=>1,'msg'=>'营业执照号不合法');
        log::write(json_encode($data));
        if (!file_exists(ROOT_PATH.'../static/upload/zip/')){ 
            mkdir(ROOT_PATH.'../static/upload/zip/');
        } 
        $zip = new ZipArchive;
        $zip_name = strtotime("now").'.zip';
        $res = $zip->open(ROOT_PATH.'../static/upload/zip/'.$zip_name,ZipArchive::CREATE|ZipArchive::OVERWRITE);
        $sucess=false;
        if ($res === TRUE) {
            if(!empty($data['legal_idcard_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['legal_idcard_pic'], 'legal_idcard_pic.png')){
                  $sucess=true;
              }
              p(ROOT_PATH.'../static/upload/'.$data['legal_idcard_pic']);
            }
            if(!empty($data['business_licence_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['business_licence_pic'], 'business_licence_pic.png')){
                  $sucess=true;
              }
              p(ROOT_PATH.'../static/upload/'.$data['business_licence_pic']);
            }
            if(!empty($data['tax_registration_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['tax_registration_pic'], 'tax_registration_pic.png')){
                  $sucess=true;
              }
              p(ROOT_PATH.'../static/upload/'.$data['tax_registration_pic']);
            }
            if(!empty($data['organization_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['organization_pic'], 'organization_pic.png')){
                  $sucess=true;
              }
              p(ROOT_PATH.'../static/upload/'.$data['organization_pic']);
            }
            if(!empty($data['Powerofattorney_pic'])){
                if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['Powerofattorney_pic'], 'Powerofattorney_pic.png')){
                    $sucess=true;
                }
                p(ROOT_PATH.'../static/upload/'.$data['Powerofattorney_pic']);
            }
            $zip->close();
        } else {
            log::write('zip failed, code:'.$res);
        }

// 		//写入数据库
// 		$data['organization_state'] = 2;
        p($zip);
        if($sucess){
		  $data['zip_url'] =  ROOT_PATH.'/upload/zip/'.$zip_name;
        }
		if(!$this->model('customer')->where('c_id='.$_SESSION['uinfo']['c_id'])->update($data)) return array('err'=>1,'msg'=>'更新失败');
		showtrace();
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