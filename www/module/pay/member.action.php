<?php
//东方付通会员相关操作
class memberAction extends null2Action
{

    public function __init()
    {
        $this->db=M('public:common');
    }
    public function init()
    {
        $ss = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
        header("Location:".$ss->memberbind());//会员绑定
//      $this->display('member.html');
    }
    
    // 获取三证接口
    //
    public function getdocuments()
    {
        if($_POST){
           $exMarketUserID = spost('exMarketUserID','s');
           $companyName = spost('companyName','s');
           $fileType = spost('fileType','s');
//            var_dump($exMarketUserID);
           if($this->db->model('customer')->select("count(0)")->where("c_id='{$exMarketUserID}'")->getOne()==0){ 
               $this->json_output(array('returnfile'=>'','statecode'=>'ERR0','message'=>'没有该公司!'));
           }
           $file=$this->db->model('customer')->select("zip_url")->where("c_id='{$exMarketUserID}'")->getOne();
           if(empty($file)){
               $this->json_output(array('returnfile'=>'','statecode'=>'ERR03','message'=>'该公司没有上传三证!'));
           }
//         var_dump($file);
        if(file_exists($file)){
          $filestrame=base64_encode(file_get_contents($file));
          $this->json_output(array('returnfile'=>$filestrame,'statecode'=>'SUCCESS','message'=>'查询成功!'));
        }else{
             $this->json_output(array('returnfile'=>'','statecode'=>'ERR03','message'=>'该公司没有上传三证!'));
        }
        }else{
            $this->json_output(array('returnfile'=>'','statecode'=>'ERR03','message'=>'其他错误!'));
        }
    }
    
}

?>