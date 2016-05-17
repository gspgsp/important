<?php
/**
*报价控制器
*/
class myquotationAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db = M('public:common')->model('purchase');
        $this->assign('pass_status',array('1'=>'上架','2'=>'下架'));
    }
    //返回报价信息/更新数据
    public function get_myquotation(){
    	//获取求购信息的c_id
    	$c_id = sget('c_id','i',0);
        $result = array();
        if($c_id>0){
            $result = M('touch:purchase')->getPurchase($c_id);
        }
        $this->json_output($result);
    }
    //上架，下架切换
    public function changestate(){
        $s_id = sget('select_id','i',0);
        if($s_id>0){
            $result = M('touch:myquotation')->changeProductState($s_id);
        }
        if($result){
            $this->json_output(array('err'=>0,'msg'=>'切换成功'));
        }else{
            $this->json_output(array('err'=>1,'msg'=>'切换失败'));
        }
    }
}