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
    	$this->display('me_logged');
    }
    //获取个人中心首页数据
    public function getPersonalCenter(){
        $this->is_ajax = true;
        if($this->user_id<0) $this->error('账户错误');
        $type1 = sget('type1','i');//$type1 1采购
        $type2 = sget('type2','i');//$type1 2报价
        $thumb = M('touch:personalAppCenter')->getUserThumb($this->user_id);
        $name = M('touch:personalAppCenter')->getUserName($this->user_id);
        $qCount = M('myapp:personalAppCenter')->getMyQuotationCount($this->user_id,$type1);
        $pCount = M('myapp:personalAppCenter')->getMyQuotationCount($this->user_id,$type2);
        $proAttCount = M('myapp:personalAppCenter')->getMyAttentionCount($this->user_id);
        $points = M('points:pointsBill')->getUerPoints($this->user_id);
        $cus_mana = M('myapp:personalAppCenter')->getMyCusManager($this->user_id);//交易员姓名
        if($name){
            $this->json_output(array('thumb'=>$thumb,'name'=>$name,'qcount'=>$qCount,'pcount'=>$pCount,'proattcount'=>$proAttCount,'points'=>$points,'cus_mana'=>$cus_mana));
        }else{
            $this->json_output(array('err'=>1,'msg'=>'没有该用户!'));
        }
    }
    //进入我的报价单
    public function enMyQuotation(){
        $this->display('me_quotation');
    }
    //获取我的报价单
    public function myQuotation(){
        $cargo_type = sget('cargo_type','i',1);
        if(!$data = M('myapp:personalAppCenter')->getMyQuotation($this->user_id,$cargo_type)) $this->json_output(array('err'=>1,'msg'=>'没有相关的数据!'));
        $this->json_output(array('err'=>0,'data'=>$data));
    }
    //进入我的采购
    public function enMyPurchase(){
        $this->display('me_purchase');
    }
    //获取我的采购
    public function myPurchase(){
        $cargo_type = sget('cargo_type','i',1);
        if(!$data = M('myapp:personalAppCenter')->getMyPurchase($this->user_id,$cargo_type)) $this->json_output(array('err'=>1,'msg'=>'没有相关的数据!'));
        $this->json_output(array('err'=>0,'data'=>$data));
    }
    //进入我的关注
    public function enMyAttention(){
        $this->display('me_attention');
    }
    //获取我的关注
    public function MyAttention(){
        $products = M('myapp:personalAppCenter')->getMyAttention($this->user_id);
        $this->json_output(array('err'=>0,'data'=>$products));
    }
    //进入我的积分
    public function enMyPoints(){
        $this->display('me_creditdetail');
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
        $this->display('me_logistics');
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
        $this->display('me_opinion');
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
    //进入添加我的关注
    public function addMyAttention(){
        $this->display('me_attentionadd');
    }
    //保存添加新的我的关注(产品)
    public function addProAttention(){
        $this->is_ajax=true; //指定为Ajax输出
        // $dataP = sdata(); //传递的参数:产品类别/产品牌号/产地
        if(empty($_POST)){
            $this->error('请添加关注产品信息');
        }
        //检查该产品是否已经关注过
        $fid = M('product:factory')->getIdsByName($_POST['address']);//根据厂家查出fid
        $pid = M('product:product')->getPidByModel($_POST['num'], $fid[0]);//根据牌号和fid查出产品id
        if($this->db->model('concerned_product')->select('product_id')->where('product_id='.$pid)->getOne()) $this->error('该产品已经关注过');

        $userContact = M('user:customerContact')->getListByUserid($this->user_id);
        $company = M('user:customer')->getCinfoById($userContact['c_id']);

        $data['user_id'] = $this->user_id;
        $data['product_name'] = L('product_type')[$_POST['kid']];//产品类别下标
        $data['model'] = $_POST['num'];
        $data['factory_name'] = $_POST['address'];
        $data['user_account_id'] = $userContact['mobile'];
        $data['staff_name'] = $userContact['name'];
        $data['customer_name'] = $company['c_name'];
        $data['product_id'] = $pid;
        $data['status'] = 1;
        $data['operate'] = 1;
        $data['groupno'] = $company['grounp_no'];
        $data['input_time'] = CORE_TIME;
        $data['input_admin'] = $_SESSION['name'];
        $data['update_time'] = CORE_TIME;
        $data['update_admin'] = $_SESSION['name'];

        if(!M('user:account')->add($data)) $this->error('添加关注失败');
        $this->success('添加关注成功');
    }
    //进入管理我的关注
    public function manageMyAttention(){
        $this->display('me_attentionmanage');
    }
    //获取我的关注(管理),同样的方法
    //我的关注全选/不选删除
    public function delMyAttention(){
        $this->is_ajax=true;
        $ids = sget('ids','a');
        $result = M('myapp:personalAppCenter')->mulDelMyAttention($ids);
        $this->json_output($result);
    }
    //积分明细
    public function creditDetail(){
        $this->display('me_mycredit');
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
        $this->display('me_creditrecord');
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
        $this->display('me_shopdetail');
    }
    //点击返回商品详情
    public function get_shopDetail(){
        $gid = sget('gid','i',0);
        $result = M('touch:creditshop')->getShopDetail($gid);
        $this->json_output($result);
    }
    /**
     *设置页面的每个cell操作
     */
    //进入姓名
    public function enName(){
        $this->display('me_set_name');
    }
    //保存姓名
    public function saveName(){
        $name = sget('name','s');
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],//有小问题
                    'name'=>$name,
                );
        $this->_saveSetData($_user);
    }
    //进入公司
    public function enCompany(){
        $this->display('me_set_company');
    }
    //保存公司
    public function saveCompany(){
        $c_name = sget('c_name','s');
        $result = M('user:customer')->getCompanyByName($c_name);//不存在则返回空数组
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],
                    'c_id'=>$result['c_id'],
                );
        $this->_saveSetData($_user);
    }
    //进入我的交易员
    public function enTrader(){
        $this->display('me_set_trader');
    }
    //保存我的交易员
    public function saveTrader(){
        $customer_manager = sget('cus_manager','i');//交易员下标
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],
                    'customer_manager'=>$customer_manager,
                );
        $this->_saveSetData($_user);
    }
    //进入密码修改
    public function enPassword(){
        $this->display('me_set_pwd');
    }
    //保存密码修改
    public function savePassword(){
        $password = sget('password','s');
        $salt=randstr(6);
        $user_model=M('system:sysUser');
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],
                    'salt'=>$salt,
                    'password'=>$user_model->genPassword($password.$salt),
                );
        $this->_saveSetData($_user);
    }
    //进入邮箱
    public function enEmail(){
        $this->display('me_set_email');
    }
    //保存邮箱
    public function saveEmail(){
        $email = sget('email','s');
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],
                    'email'=>$email,
                );
        $this->_saveSetData($_user);
    }
    //进入QQ
    public function enQq(){
        $this->display('me_set_qq');
    }
    //保存QQ
    public function saveQq(){
        $qq = sget('qq','s');
        $_user=array(
                    'update_time'=>CORE_TIME,
                    'update_admin'=>$_SESSION['name'],
                    'qq'=>$qq,
                );
        $this->_saveSetData($_user);
    }
    //保存个人设置
    private function _saveSetData($_user){
        if(!$this->db->model('customer_contact')->where('user_id=$this->user_id')->update($_user)) $this->json_output(array('err'=>1,'msg'=>'修改失败'));
        $this->json_output(array('err'=>0,'msg'=>'修改成功'));
    }
    //退出登录
    public function logOut(){
        if($this->user_id>0){
            M('user:passport')->setSession();
        }
        $this->json_output(array('err'=>0,'msg'=>'退出成功'));
    }

}