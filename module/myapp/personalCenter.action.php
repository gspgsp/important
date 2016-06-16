<?php
/**
*个人中心控制器-app
*/
class personalCenterAction extends homeBaseAction
{
	public function __init() {
		$this->db=M('public:common')->model('customer_contact');
    }
    //进入个人中心
    public function init(){
    	$this->display('pcenter');
    }
    //获取个人中心首页数据
    public function getPersonalCenter(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
        $type1 = sget('type1','i');//$type1 1采购
        $type2 = sget('type2','i');//$type1 2报价
        $thumb = M('touch:personalcenter')->getUserThumb($this->user_id);
        $name = M('touch:personalcenter')->getUserName($this->user_id);
        $qCount = M('myapp:personalCenter')->getMyQuotationCount($this->user_id,$type1);
        $pCount = M('myapp:personalCenter')->getMyQuotationCount($this->user_id,$type2);
        $proAttCount = M('myapp:personalCenter')->getMyAttentionCount($this->user_id);
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
        $cus_mana = M('myapp:personalCenter')->getMyCusManager($this->user_id);//交易员姓名
        if($name){
            $this->json_output(array('thumb'=>$thumb,'name'=>$name,'qcount'=>$qCount,'pcount'=>$pCount,'proattcount'=>$proAttCount,'points'=>$points,'cus_mana'=>$cus_mana));
        }else{
            $this->json_output(array('err'=>1,'msg'=>'没有该用户!'));
        }
    }
    //进入我的报价单
    public function enMyQuotation(){
        $this->display('myquotation');
    }
    //获取我的报价单
    public function myQuotation(){
        $cargo_type = sget('cargo_type','i',1);
        if(!$data = M('myapp:personalCenter')->getMyQuotation($this->user_id,$cargo_type)) $this->json_output(array('err'=>1,'msg'=>'没有相关的数据!'));
        $this->json_output(array('err'=>0,'data'=>$data));
    }
    //进入我的采购
    public function enMyPurchase(){
        $this->display('mypurchase');
    }
    //获取我的采购
    public function myPurchase(){
        $cargo_type = sget('cargo_type','i',1);
        if(!$data = M('myapp:personalCenter')->getMyPurchase($this->user_id,$cargo_type)) $this->json_output(array('err'=>1,'msg'=>'没有相关的数据!'));
        $this->json_output(array('err'=>0,'data'=>$data));
    }
    //进入我的关注
    public function enMyAttention(){
        $this->display('myattention');
    }
    //获取我的关注
    public function MyAttention(){
        $products = M('myapp:personalCenter')->getMyAttention($this->user_id);
        $this->json_output(array('err'=>0,'data'=>$products));
    }
    //进入我的积分
    public function enMyPoints(){
        $this->display('mypoints');
    }
    //获取我的积分
    public function getMyPoints(){
        if($this->user_id>0){
            $points = M('points:pointsBill')->getUerPoints($this->user_id);
            $result = M('touch:creditshop')->getCreditShop();
        }
        $this->json_output(array('points'=>$points,'shop'=>$result));
    }
    //进入我的物流
    public function enMyTrans(){
        $this->display('mytrans');
    }
    //获取我的物流
    public function getMyTrans(){
        //$this->db->model('')
    }
    //进入我的设置
    public function enMySet(){
        $this->display('me_set');
    }
    //获取我的设置
    public function getMySet(){
        $set = M('user:customerContact')->getUserInfoByid($this->user_id);
        $set['c_name'] = M('user:customer')->getCinfoById($set['c_id']);
        $this->json_output(array('err'=>0,'data'=>$set));
    }
    //进入我的意见反馈
    public function enMyFeedBack(){
        $this->display('myfeedback');
    }
    //获取我的意见反馈
    public function getMyFeedBack(){
        //
    }
    /**
     *跳转到下个界面，功能操作
     */
    //报价搜索
    public function doQuoSearch(){
        $this->is_ajax=true;
        //搜素关键字
        $keywords = sget('keywords','s');
        //存取数值
        $temp = array();
        $result = array();
        if(empty($keywords)){
            $this->error('请输入关键字');
        }
        //筛选产品类型
        $p_types = array('1'=>'HDPE','2'=>'LDPE','3'=>'LLDPE','4'=>'PP','5'=>'PVC');
        if(in_array($keywords, $p_types)){
            $keyValue = $this->_getProKey($p_types,$keywords);
        }
        //1,上架2,下架
        $type = sget('type','s');
        $where="(fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}') and pur.user_id={$this->user_id}";
        if($type == '1'){
            $where.=" and pur.shelve_type=1";
            $data = $this->db->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.number,pur.store_house,pur.input_time,pur.shelve_type')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reverseData($data);
            $this->json_output($resultData);
        }elseif ($type == '2') {
            $where.=" and pur.shelve_type=2";
            $data = $this->db->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.number,pur.store_house,pur.input_time,pur.shelve_type')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reverseData($data);
            $this->json_output($resultData);
        }else{
            $data = $this->db->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.number,pur.store_house,pur.input_time,pur.shelve_type')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reverseData($data);
            $this->json_output($resultData);
        }
    }
    //转换数据
    private function _reverseData($data){
        //遍历data
            foreach ($data as $value) {
                $temp['id'] = $value['id'];
                $temp['p_id'] = $value['p_id'];
                $temp['model'] = $value['model'];
                $temp['product_type'] = $value['product_type'];
                $temp['unit_price'] = $value['unit_price'];
                $temp['f_name'] = $value['f_name'];
                $temp['number'] = $value['number'];
                $temp['store_house'] = $value['store_house'];
                $temp['input_time'] = $value['input_time'];
                $temp['shelve_type'] = $value['shelve_type'];
                $result[]=$temp;
                unset($temp);
            }
            return $result;
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
        $result = M('touch:myquotation')->refreshMulCell($ids, $up, $down, $del);
        $this->json_output($result);
    }
    //求购搜索
    public function doPurSearch(){
        $this->is_ajax=true;
        //搜素关键字
        $keywords = sget('keywords','s');
        //存取数值
        $temp = array();
        $result = array();
        if(empty($keywords)){
            $this->error('请输入关键字');
        }
        //筛选产品类型
        $p_types = array('1'=>'HDPE','2'=>'LDPE','3'=>'LLDPE','4'=>'PP','5'=>'PVC');
        if(in_array($keywords, $p_types)){
            $keyValue = $this->_getProKey($p_types,$keywords);
        }
        //1.待审核  2.审核通过  3.洽谈中  4.交易成功   5.无效 6:过期
        $type = sget('status','s');
        $where="(fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}') and pur.user_id={$this->user_id}";
        if($type == '1'){
            $where.=" and pur.status=1";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }elseif ($type == '2') {
            $where.=" and pur.status=2";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }elseif ($type == '3') {
            $where.=" and pur.status=3";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }elseif ($type == '4') {
            $where.=" and pur.status=4";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }elseif ($type == '5') {
            $where.=" and pur.status=5";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }elseif ($type == '6') {
            $where.=" and pur.status=6";
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }else{
            $data = $this->db->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getAll();
            $resultData = $this->_reversePurData($data);
            $this->json_output($resultData);
        }
    }
    //转换数据
    private function _reversePurData($data){
        //遍历data
            foreach ($data as $value) {
                $temp['model'] = $value['model'];
                $temp['product_type'] = $value['product_type'];
                $temp['unit_price'] = $value['unit_price'];
                $temp['f_name'] = $value['f_name'];
                $temp['number'] = $value['number'];
                $temp['store_house'] = $value['store_house'];
                $temp['input_time'] = $value['input_time'];
                $result[]=$temp;
                unset($temp);
            }
            return $result;
    }
    //获取当前类型的键
    private function _getProKey($p_types,$keywords){
        foreach ($p_types as $key => $value) {
            if($value == strtoupper($keywords))
                return $key;
        }
    }
    //我的关注全选/不选删除
    public function delMyAttention(){
        $this->is_ajax=true;
        $ids = sget('ids','a');
        $result = M('myapp:personalCenter')->mulDelMyAttention($ids);
        $this->json_output($result);
    }
    //积分明细
    public function creditDetail(){
        $this->display('creditdetail');
    }
    //返回积分明细
    public function get_creditdetail(){
        if($this->user_id>0){
            $points = M('points:pointsBill')->getUerPoints($this->user_id);
            $result = M('touch:creditdetail')->getCreditDetail($this->user_id);
        }
        $this->json_output(array('points'=>$points,'detail'=>$result));
    }
    //签到、兑换路径:/myapp/sign
    //兑换记录
    public function creditRecord(){
        $this->display('creditrecord');
    }
    //返回兑换记录
    public function get_creditRecord(){
        if($this->user_id>0){
            $result = M('touch:creditRecord')->getCreditRecord($this->user_id);
        }
        $this->json_output($result);
    }
    //商品详情页
    public function shopDetail(){
        $this->display('shopdetail');
    }
    //点击返回商品详情
    public function get_shopDetail(){
        $gid = sget('gid','i',0);
        $result = M('touch:creditshop')->getShopDetail($gid);
        $this->json_output($result);
    }
}