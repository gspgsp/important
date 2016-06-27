<?php


class indexAction extends userBaseAction{


    /**
     * 个人中心首页
     *
     */
    public function init()
	{
		$this->act='index';
        $userid=$this->user_id;
        //个人信息
        $list=M('user:customerContact')->getCustomerInFoById($userid);

        //我的关注列表

        //$data = M('user:productAttentions')->getAttentionvalue();
      //  p($data);
        $this->assign('data',$list);



		$this->display('index');
	}

//    //获取关注的列表
//    private function getAttentionvalue(){
//        $list = $this->db->model('concerned_product')->where('user_id='.$this->user_id)
//            ->order("input_time desc")
//            ->limit('6');
//        foreach ($list['data'] as $key => $value) {
//            $list['data'][$key]['status'] = L('attention_status')[$value['status']];
//            $list['data'][$key]['operate'] = L('operate')[$value['operate']];
//            $list['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
//            $list['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
//        }
//        return array('detail'=>$list['data']);
//    }
}