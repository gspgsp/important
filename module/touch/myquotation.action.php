<?php
/**
*报价控制器
*/
class myquotationAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db = M('public:common')->model('purchase');
        // $this->assign('pass_status',array('1'=>'上架','2'=>'下架'));
    }
    public function init(){
        $this->display('myquotation');
    }
    //返回报价信息/更新数据
    public function get_myquotation(){
    	//获取求购信息的user_id
        $user_id = $_SESSION['uid'];
        $result = array();
        if($user_id>0){
            $result = M('touch:purchase')->getPurchase($user_id);
        }
        $this->json_output($result);
    }
    //上架，下架切换
    public function changestate(){
        $p_id = sget('p_id','i',0);
        if($p_id>0){
            $result = M('touch:myquotation')->changeProductState($p_id);
        }
        if($result){
            $this->json_output(array('err'=>0,'msg'=>'切换成功'));
        }else{
            $this->json_output(array('err'=>1,'msg'=>'切换失败'));
        }
    }
}