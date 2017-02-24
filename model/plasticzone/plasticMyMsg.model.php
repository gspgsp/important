<?php
/**
*我的消息-gsp
*/
class plasticMyMsgModel extends model
{
	public function __construct() {
	    $this->db=M('public:common');
        parent::__construct(C('db_default'), 'purchase');
    }
    public function getMyMsg($user_id,$page=1,$size=10,$type){
    	//别人发布的(我回复别人的)
    	// $where3 = "user_id=$user_id";
     //    $pids = $this->model('weixin_plasticzone')->select('pur_id')->where($where3)->getAll();
     //    foreach ($pids as $value) {
     //    	$pur_id[] = $value['pur_id'];
     //    }
     //    $pids = implode(',', $pur_id);
    	//我发布的/别人发布的
    	// $where = " pur.sync=6 and pur.type=$type and (pur.user_id=$user_id or pur.id in ($pids)) ";
        $where = " pur.sync = 6 and pur.user_id=$user_id and pur.type=$type ";
    	$data = $this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.type,pur.content,pur.input_time')->from('purchase pur')
            ->leftjoin('product pro','pur.p_id=pro.id')
            ->leftjoin('factory fa','pro.f_id=fa.fid')
            ->page($page,$size)
            ->where($where)
            ->order('pur.input_time desc')
            ->getPage();
        foreach ($data['data'] as &$value) {
            // $value['product_type'] = L('product_type')[$value['product_type']];
            $value['input_time'] = date("m-d H:i",$value['input_time']);
            //供求或采购方
            $value['b_and_s'] = $this->_getNeedsHost($value['p_id'],$user_id);
            //最新成交价格
            $value['deal_price'] = $this->_getDealPrice($value['p_id'],$user_id);
            //网友说
            $value['says'] = $this->_getLiuYan($value['id']);
            //显示的内容
            if(empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = '';
                }else{
                $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            }elseif(!empty($value['content'])){
                if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                    $value['contents'] = $value['content'];
                    $value['b_and_s'] = '';
                    $value['deal_price'] = '';
                }else{
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
        }
        return $data;
    }
    //获取我的消息(留言)
    public function getMyComment($user_id,$page=1,$size=10,$sync=6){
        //别人发布的(我回复别人的)
        // $where3 = "user_id=$user_id";
        // $pids = $this->model('weixin_plasticzone')->select('pur_id')->where($where3)->getAll();
        // foreach ($pids as $value) {
        //     $pur_id[] = $value['pur_id'];
        // }
        // $pids = implode(',', $pur_id);
        if(is_array($sync)){
            $where = " pur.sync in (".implode(',',$sync).") and pur.user_id=$user_id and pz.send_id=$user_id ";
        }else{
            $where = " pur.sync=$sync and pur.user_id=$user_id and pz.send_id=$user_id ";
        }

//         //我发布的/别人发布的
//         // $where = " pur.sync=6 and (pur.user_id=$user_id or pur.id in ($pids)) ";
//         $data = $this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.type,pur.content,pur.input_time')->from('purchase pur')
//             ->join('product pro','pur.p_id=pro.id')
//             ->join('factory fa','pro.f_id=fa.fid')
//             ->join('weixin_plasticzone pz','pur.id=pz.pur_id')
//             ->page($page,$size)
//             ->where($where)
//             ->order('pur.input_time desc')
//             ->getPage();
        $sql = "SELECT DISTINCT `pur`.`id`, `pur`.`p_id`, `pur`.`user_id`, `pro`.`model`, `pur`.`unit_price`, `pur`.`store_house`, `fa`.`f_name`, `pur`.`type`, `pur`.`content`, `pur`.`input_time`
            FROM `p2p_purchase` `pur`
            LEFT JOIN `p2p_product` `pro` ON pur.p_id=pro.id
            LEFT JOIN `p2p_factory` `fa` ON pro.f_id=fa.fid
            LEFT JOIN `p2p_weixin_plasticzone` `pz` ON pur.id=pz.pur_id
            WHERE ".$where." ORDER BY pur.input_time desc  limit ".($page-1)*$size.",".$size;
        $data =$this->db->getAll($sql);
        $data['data'] = $data;
        foreach ($data['data'] as $key=> &$value) {
            // $value['product_type'] = L('product_type')[$value['product_type']];
                $value['input_time'] = date("m-d H:i",$value['input_time']);
                $value['says'] = $this->_getLiuYan($value['id']);
                $value['deal_price'] = $this->_getDealPrice($value['p_id'],$user_id);
                $value['b_and_s'] = $this->_getNeedsHost($value['p_id'],$user_id);
                //显示的内容
//                 if(empty($value['content'])){
//                     $value['contents'] = $value['unit_price'].'/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
//                 }elseif(!empty($value['content'])){
//                     if(empty($value['unit_price']) && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
//                         $value['contents'] = $value['content'];
//                     }else{
//                         $value['contents'] = $value['unit_price'].'/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
//                     }
//                 }
                if(empty($value['content'])){
                    if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                        $value['contents'] = '';
                    }else{
                        $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                    }
                }elseif(!empty($value['content'])){
                    if($value['unit_price']==0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])){
                        $value['contents'] = $value['content'];
                        $value['b_and_s'] = '';
                        $value['deal_price'] = '';
                    }else{
                        $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                    }
                }

        }
        return $data;
    }
    //删除我的报价或供给
    public function deleteMyMsg($id){
        $this->db->startTrans();
        if($this->model('weixin_plasticzone')->select('count(0)')->where('pur_id='.$id)->getOne()>0){
            $this->model('weixin_plasticzone')->where('pur_id='.$id)->delete();
        }
        if($this->model('purchase')->select('count(0)')->where('id='.$id)->getOne()>0){
            $this->model('purchase')->where('id='.$id)->delete();
        }
        if($this->db->commit()){
            return array('err'=>0,'msg'=>'删除成功');
        }else{
            $this->db->rollback();
            return array('err'=>2,'msg'=>'删除失败');
        }
    }
    //删除报价或供给单条回复
    public function deleteRepeat($id){
        $result = $this->model('weixin_plasticzone')->where('id='.$id)->delete();
        return $result==true?array('err'=>0,'msg'=>'删除成功'):array('err'=>2,'msg'=>'删除失败');
    }
    //塑料圈联系人的-我的消息
    public function getZoneContactMsg($user_id,$type,$page=1,$size=10){
        switch ($type) {//1:我接受的 2:我发送的
            case 1:
                $where = " user_id=$user_id ";
                break;
            case 2:
                $where = " send_id=$user_id ";
                break;
        }
        $data = $this->model('weixin_msg')->select('id,send_id ,user_id,is_read,content,input_time')
            ->page($page,$size)
            ->where($where)
            ->order('input_time desc')
            ->getPage();
            foreach ($data['data'] as &$value) {
                $value['send_name'] = $this->_getCustomerCon($value['send_id'])['name'];//网名
                $value['user_name'] = $this->_getCustomerCon($value['user_id'])['name'];//网名
                $value['input_time'] = M('myapp:personalAppCenter')->changeTime($value['input_time']);
                $value['thumb'] = $this->getPlasticUserInfo($value['send_id'])['thumb'];
                if($type==1){
                    $value['name'] = $this->getPlasticUserInfo($value['send_id'])['name'];
                    $value['c_name'] = $this->getPlasticUserInfo($value['send_id'])['c_name'];
                }
                //标为已读
                $this->model('weixin_msg')->where("id=".$value['id'])->update(array('is_read'=>1));
            }
        return $data;
    }
    //根据类型获取会员头像或公司
    public function getPlasticUserInfo($userid){
        $data = M('user:customerContact')->getCustomerInFoById($userid);
        if(empty($data['thumbqq']))
        {
            if (strstr($data['thumb'], 'http')) {
                $data['thumb']= $data['thumb'];
            } else {
                if(empty($data['thumb'])){
                    $data['thumb']= "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                }else{
                    $data['thumb']= FILE_URL."/upload/".$data['thumb'];
                }
            }
        }else{
            $data['thumb']=$data['thumbqq'];
        }
        return $data;
    }
    //获取留言消息
    public function _getLiuYan($id){
        $where = " pur_id=$id ";
        $says = $this->model('weixin_plasticzone')->select('id,send_id as rev_id,user_id,is_read,content,input_time')->where($where)->order('input_time asc')->getAll();
        foreach ($says as &$v) {
            $v['rev_name'] = M('user:customerContact')->getListByUserid($v['rev_id'])['name'];//网名
            $v['user_name'] = M('user:customerContact')->getListByUserid($v['user_id'])['name'];//网名
            $v['input_time'] = date("m-d H:i",$v['input_time']);
        }
        $this->model('weixin_plasticzone')->where($where)->update(array('is_read'=>1));//标为已读
        return $says;
    }
    //获取联系人信息
    private function _getCustomerCon($userid){
        return M('user:customerContact')->getListByUserid($userid);
    }
    //获取供求或采购方
    private function _getNeedsHost($p_id,$user_id){
        $result = array();
//         $user_pur = $this->model('purchase')->where("p_id=$p_id and user_id=$user_id")->select('id,user_id,model,fname')->limit('0,1')->getRow();//同一个p_id知趣一条
        $where1 = "pur.p_id=$p_id and pur.user_id != $user_id and pur.type=1";
        $where2 = "pur.p_id=$p_id and pur.user_id != $user_id and pur.type=2";
        $buy = $this->_getBuySale($where1);
        $sale = $this->_getBuySale($where2);
        array_push($result, $buy,$sale);
        return $result;
    }
    //供求或采购方
    private function _getBuySale($where){
        return $this->model('purchase')->select('pur.id,con.name,con.mobile,con.user_id,cus.c_name')->from('purchase pur')
            ->join('customer_contact con','pur.user_id=con.user_id')
            ->join('customer cus','cus.c_id=con.c_id')
            ->where($where)
            ->order('pur.input_time desc')
            ->limit('0,1')
            ->getRow();
    }
    //获取最新成交价
    private function _getDealPrice($p_id,$user_id){
        return $this->model('purchase')->select('unit_price')->where("p_id=$p_id and type=2 and user_id != $user_id")->order('input_time desc')->limit('0,1')->getOne();
    }
}