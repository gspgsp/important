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
//         log::write(json_encode($data));
        if (!file_exists(ROOT_PATH.'../static/upload/zip/')){ 
            mkdir(ROOT_PATH.'../static/upload/zip/');
        } 
        if (!extension_loaded('zip')) {
            dl('zip.so');
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
            }
            if(!empty($data['business_licence_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['business_licence_pic'], 'business_licence_pic.png')){
                  $sucess=true;
              }
            }
            if(!empty($data['tax_registration_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['tax_registration_pic'], 'tax_registration_pic.png')){
                  $sucess=true;
              }
            }
            if(!empty($data['organization_pic'])){
              if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['organization_pic'], 'organization_pic.png')){
                  $sucess=true;
              }
            }
            if(!empty($data['Powerofattorney_pic'])){
                if($zip->addFile(ROOT_PATH.'../static/upload/'.$data['Powerofattorney_pic'], 'Powerofattorney_pic.png')){
                    $sucess=true;
                }
            }
            $zip->close();
            unset($zip);
        } else {
            log::write('zip failed, code:'.$res);
        }
        
// 		//写入数据库
		$data['organization_state'] = 2;
        if($sucess){
		  $data['zip_url'] =  ROOT_PATH.'/upload/zip/'.$zip_name;
        }
        $billingdata['tax_id'] = $data['tax_id'];
        $billingdata['invoice_address'] = $data['invoice_address'];
        $billingdata['invoice_tel'] = $data['invoice_tel'];
        $billingdata['invoice_bank'] = $data['invoice_bank'];
        $billingdata['invoice_account'] = desEncrypt($data['invoice_account']);//加密
        $billingdata['user_id'] = $_SESSION['uinfo']['user_id'];
        $billingdata['c_id']=$_SESSION['uinfo']['c_id'];
        unset($data['tax_id']);
        unset($data['invoice_address']);
        unset($data['invoice_tel']);
        unset($data['invoice_bank']);
        unset($data['invoice_account']);
        $this->db->startTrans();
        if(!$this->model('customer')->where('c_id='.$_SESSION['uinfo']['c_id'])->update($data)) return array('err'=>1,'msg'=>'更新失败');
        if(!empty(M('public:common')->model('customer_billing')->where('c_id='.$_SESSION['uinfo']['c_id'])->getOne())){
            $billingdata['update_time']=time();
            $billingdata['update_admin']=$_SESSION['uinfo']['user_id'];
            if(!M('public:common')->model('customer_billing')->where('c_id='.$billingdata['c_id'])->update($billingdata)) return array('err'=>1,'msg'=>'更新失败');
        }else{
            $billingdata['status']=0;
            $billingdata['display_status']=1;
            $billingdata['customer_manager']=$_SESSION['uinfo']['customer_manager'];
            $billingdata['input_time']=time();
            $billingdata['input_admin']=$_SESSION['uinfo']['user_id'];
            if(!M('public:common')->model('customer_billing')->add($billingdata))return array('err'=>1,'msg'=>'新增失败');
        }
		if($this->db->commit()){
		    return array('err'=>0,'msg'=>'验证通过,更新成功');
		}else{
		    $this->db->rollback();
		    return array('err'=>1,'msg'=>'生成失败:'.$this->db->getDbError());
		}
// 		return array('err'=>0,'msg'=>'验证通过,更新成功');
	}
	/**
	 * 判断身份证号格式是否正确
	 * @return bool
	 */
	private function is_idcard($idcard) {
		return  preg_match('/^(\s)*(\d{15}|\d{18}|\d{17}x)(\s)*$/i', $idcard);
	}
}