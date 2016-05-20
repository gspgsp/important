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
    //返回报价信息
    public function get_myquotation(){
        //获取求购信息的user_id
        if(!$_SESSION['uid'])
            {
                $this->json_output(array('err'=>1,'msg'=>'请求超时,请重新登录!'));
            }
        $user_id = $_SESSION['uid'];
        $result = array();
        if($user_id>0){
            $result = M('touch:purchase')->getPurchase($user_id);
        }
        $this->json_output($result);
    }
    //单个上架，下架切换
    public function changestate(){
        //当前求购的id
        $id = sget('id','i',0);
        if($id>0){
            $result = M('touch:myquotation')->changeProductState($id);
        }
        $this->json_output($result);
    }

    //单个更新报价单
    public function refreshCell(){
        $id = sget('id','i',0);
        $data = sget('qdata','a');
        $p_id = sget('pid','i',0);
        $result = M('touch:myquotation')->refreshCell($id, $data, $p_id);
        $this->json_output($result);
    }
    //批量更新报价单
    public function refreshMulCell(){
        //取出所有的id
        $ids = sget('ids','a');
        $up = sget('up','i');
        $down = sget('down','i');
        $del = sget('del','i');
        $result = $this->M('touch:myquotation')->refreshMulCell($ids, $up, $down, $del);
        $this->json_output($result);
    }

}