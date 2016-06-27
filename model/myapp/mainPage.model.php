<?php
/**
*应用首页模型-app
*/
class mainPageModel extends model
{

    public function __construct() {
        parent::__construct(C('db_default'), 'info');
    }
    //获取今日头条、原油价格
    public function getInfos($type){
        if($type == 1){
            return $this->model('info')->select('id,title,input_time')->where("cate_id in (29,30,31,32,33)")->order('input_time desc')->limit('0,5')->getAll();
        }elseif($type == 2){
            $oils = $this->model('oil_price')->order('input_time desc')->limit('0,5')->getAll();
            foreach ($oils as $key => $value) {
                $oils[$key]['type'] = $value['type']==0 ?'WTI':'BRENT';
            }
            return $oils;
        }
    }
    //获取所有的调价动态
    public function getAllPriceFloor($page=1,$size=20,$sortField='input_time',$sortOrder='desc'){
        $list = $this->model('oil_price')
            ->page($page,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['type'] = $value['type'] == 0? 'WTI':'BRENT';
            }
        return $list;
    }
    //获取搜索结果数据(4种方式)
    public function getAllSearchRes($keywords,$page=1,$size=8,$sortField='input_time',$sortOrder='desc'){
        //筛选产品类型
        $p_types = array('1'=>'HDPE','2'=>'LDPE','3'=>'LLDPE','4'=>'PP','5'=>'PVC');
        if(in_array($keywords, $p_types)){
            $keyValue = $this->_getProKey($p_types,$keywords);
        }
        $where="fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}'";

            $data = $this->model('purchase')->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->page($page,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
            foreach ($data['data'] as $key => $value) {
                $data['data'][$key]['twoData'] = $this->_getOperateRes($value['p_id']);
            }
            return $data;
    }
    //获取当前类型的键
    // private function _getProKey($p_types,$keywords){
    //  foreach ($p_types as $key => $value) {
    //      if($value == strtoupper($keywords))
    //          return $key;
    //  }
    // }
    //获取排序后的数据
    public function getSortedData($stype,$page=1,$size=8){
        if($stype == 1){
            $sortField = 'unit_price';
            $sortOrder = 'desc';
        }elseif ($stype == 2) {
            $sortField = 'unit_price';
            $sortOrder = 'asc';
        }elseif ($stype == 3) {
            $sortField = 'input_time';
            $sortOrder = 'desc';
        }
        $data = $this->model('purchase')->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->page($page,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
            return $data;
    }
    //获取筛选后的数据
    public function getCheckeddData($ctype,$page=1,$size=8,$sortField='input_time',$sortOrder='desc'){
        $where="pur.type='{$ctype}'";
        $data = $this->model('purchase')->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->page($page,$size)
            ->where($where)
            ->order("$sortField $sortOrder")
            ->getPage();
            return $data;
    }
    //搜索结果列表的操作按钮，下三角
    private function _getOperateRes($p_id){
        $opres = $this->model('purchase')->where('p_id='.$p_id)->select('id,p_id,user_id,c_id,number,unit_price,provinces,input_time')->order('unit_price desc,input_time desc')->limit('0,2')->getAll();
        foreach ($opres as $key => $value) {
            $opres[$key]['provinces'] = $this->model('lib_region')->select('name')->where('id='.$value['provinces'])->getOne();
            $opres[$key]['company'] = $this->model('customer')->select('c_name')->where('c_id='.$value['c_id'])->getOne();
            $opres[$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
        }
        return $opres;
    }
    //获取点击查看/委托
    public function getCheckDelegate($otype,$id){

        $where = "pur.id=$id";
        $chDeRes = array();
        $data = $this->from('purchase pur')->select('pur.id,pur.p_id,pur.number,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.provinces,pur.store_house,pur.c_id,pur.input_time,pur.cargo_type,pur.user_id')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getRow();
            $contact = M('user:customerContact')->getListByUserid($data['user_id']);
        if($otype == 1){
            //产品信息
            $chDeRes['product_type'] = L('product_type')[$data['product_type']];
            $chDeRes['model'] = $data['model'];
            $chDeRes['unit_price'] = $data['unit_price'];
            $chDeRes['number'] = $data['number'];
            $chDeRes['f_name'] = $data['f_name'];
            $chDeRes['store_house'] = $data['store_house'];
            $chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地,汉字型
            $chDeRes['delivery_place'] = $data['provinces'];//交货地,数字型
            $chDeRes['c_name'] = M('user:customer')->getCinfoById($data['c_id'])['c_name'];//公司名
            $chDeRes['input_time'] = $data['input_time'] >1000 ? date("Y-m-d",$data['input_time']):'-';//发布时间
            $chDeRes['delivertime'] = $data['cargo_type'] ==1 ? '现货':'期货';//交货时间
            //联系人
            $chDeRes['con_name'] = $contact['name'];
            $chDeRes['mobile'] = $contact['mobile'];
            $chDeRes['qq'] = $contact['qq'];
        }elseif ($otype == 2) {
            //委托交易
            $chDeRes['product_type'] = L('product_type')[$data['product_type']];
            $chDeRes['model'] = $data['model'];
            $chDeRes['unit_price'] = $data['unit_price'];
            $chDeRes['number'] = $data['number'];
            $chDeRes['f_name'] = $data['f_name'];
            $chDeRes['store_house'] = $data['store_house'];
            $chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地,汉字型
            $chDeRes['delivery_place'] = $data['provinces'];//交货地,数字型
            $chDeRes['delivertime'] = $data['cargo_type'] ==1 ? '现货':'期货';//采购方式
            //我的信息
            $chDeRes['con_name'] = $contact['name'];
            $chDeRes['mobile'] = $contact['mobile'];
            $chDeRes['c_name'] = M('user:customer')->getCinfoById($contact['c_id'])['c_name'];
        }
        return $chDeRes;
    }
    //获取分类的数据
    public function getProductTypes(){
        $types = array();
        $type1['name'] = '高密度聚乙烯';
        $type1['type'] = 'HDPE';
        $type1['model'] = array('700F','5502BN','BL3');
        array_push($types, $type1);
        $type2['name'] = '低密度聚乙烯';
        $type2['type'] = 'LDPE';
        $type2['model'] = array('2420H','2100TN00','2119');
        array_push($types, $type2);
        $type3['name'] = '线性聚乙烯';
        $type3['type'] = 'LLDPE';
        $type3['model'] = array('218W','21HN','7042');
        array_push($types, $type3);
        $type4['name'] = '聚丙烯';
        $type4['type'] = 'PP';
        $type4['model'] = array('JC-160','JH330B','JH-350');
        array_push($types, $type4);
        $type5['name'] = '聚氯乙烯';
        $type5['type'] = ' PVC';
        $type5['model'] = array('373MC','C100V','C12/62V');
        array_push($types, $type5);
        return empty($types)?false:$types;
    }
    //获取分类关键字
    public function getProductTypeData($protype){
        $apply = L('process_level');
        $factory = $this->model('factory')->where('product_type='.$protype)->select('f_name')->order('sort,desc')->limit('0,10')->getAll();
        $region = array(
            '上海',
            '江苏',
            '浙江',
            '山东',
            '广东',
            '山西',
            '福建',
            '四川',
            '重庆',
            '安徽',
            '辽宁',
            '吉林',
            '湖北',
            '湖南',
            '河南',
            '江西',
            '广西',
            '海南',
            );
        $typeData = array($apply,$factory,$region);
        return $typeData;
    }
    //点击三个关键字出结果
    public function getKeyWordsData($protype,$apply,$factory,$region){
        $where = "pro.product_type=$protype";
        if(in_array($apply, L('process_level'))){
            $applyValue = $this->_getProKey(L('process_level'),$apply);
        }
        if(!empty($apply)){
            $where .= " and pro.process_type=$applyValue";//应用的下标值
        }elseif (!empty($factory)) {
            $where .= " and fa.f_name=$factory";
        }elseif (!empty($region)) {
            $where .= " and pur.provinces=$region";
        }
        $data = $this->model('purchase')->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->order("$sortField $sortOrder")
            ->getAll();
            //数据处理
            foreach ($data as $key => $value) {
                $data[$key]['product_type'] = L('product_type')[$data['product_type']];
                $data[$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d",$value['input_time']):'-';
            }
        return $data;
    }
    //获取当前类型的键
    private function _getProKey($p_types,$keywords){
        foreach ($p_types as $key => $value) {
            if($value == strtoupper($keywords))
                return $key;
        }
    }
    //大户报价数据
    public function getLargeBidData($otype,$page=1,$size=8,$sortField='input_time',$sortOrder='desc'){
        if($otype==1){
            $sortField = 'price';
            $sortOrder='asc';
        }elseif ($otype==2) {
            $sortField = 'price';
            $sortOrder='desc';
        }elseif ($otype==3) {
            $sortField='input_time';
            $sortOrder='desc';
        }
        $largrBid = $this->model('big_offers')->select('bio.id,bio.cid,bio.type,bio.model,bio.factory,bio.price,bio.input_time')->from('big_offers bio')
            ->page($page,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
            foreach ($largrBid['data'] as $key => $value) {
                $largrBid['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d",$value['input_time']):'-';
            }
        return $largrBid;
    }
    //大户报价下三角数据
    public function getLargeBidRes($id){
        $bigOff = $this->model('big_offers')->where('id='.$id)->getRow();
        $newBig = array();
        $data = $this->model('big_offers')->where("model='{$bigOff['model']}' and factory='{$bigOff['factory']}'")->order('input_time desc')->limit('0,2')->getAll();
        foreach ($data as $key => $value) {
            $bigCli = $this->model('big_client')->where('id='.$value['cid'])->select('gsname,phone')->getRow();
            $newBig[$key]['gsname'] = $bigCli['gsname'];
            $newBig[$key]['phone'] = $bigCli['phone'];
            $newBig[$key]['price'] = $value['price'];
            $newBig[$key]['num'] = $value['num'];
            $newBig[$key]['address'] = $value['address'];
            unset($bigCli);
        }
        return $newBig;
    }
    //获取筛选条件
    public function getLargeChoseData(){
        $company = $this->model('big_client')->select('gsname')->order('lasttime desc')->limit('0,20')->getAll();
        $factory = $this->model('big_offers')->select('factory')->order('input_time desc')->limit('0,30')->getAll();
        $address = $this->model('big_offers')->select('address')->order('input_time desc')->limit('0,30')->getAll();
        $choseData = array(
            'company'=>$company,
            'factory'=>$factory,
            'address'=>$address,
            );
        return $choseData;
    }
    //获取大客户点击确定筛选结果
    public function getLargeChoseRes($company,$factory,$address,$page=1,$size=8,$sortField='input_time',$sortOrder='desc'){
        $where = "1";
        if(!empty($company)){
            $where .= " and bic.company=$company";//应用的下标值
        }elseif (!empty($factory)) {
            $where .= " and bio.factory=$factory";
        }elseif (!empty($address)) {
            $where .= " and bio.address=$address";
        }
        $choseRes = $this->model('big_offers')->select('bio.id,bio.cid,bio.type,bio.model,bio.factory,bio.price,bio.input_time')->from('big_offers bio')
            ->join('big_client bic','bio.cid=bic.id')
            ->page($page,$size)
            ->where($where)
            ->order("$sortField $sortOrder")
            ->getPage();
        foreach ($choseRes['data'] as $key => $value) {
            $choseRes['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d",$value['input_time']):'-';
        }
        return $choseRes;
    }
    //获取物性表搜索页结果数据
    public function getPhysicalResData($keywords,$page=1,$size=10){
        $phyData = $this->model('physical')
                ->where(" name like '%$keywords%' or company like '%$keywords%' ")
                ->page($page,$size)
                ->order('input_time desc')
                ->getPage();
                return $phyData;
    }
    //获取物性表搜索页详情数据
    public function getPhysicalDetailData($lid){
        return $this->model('physical')->where('lid='.$lid)->getRow();
    }
    //获取供求(公海的报价和求购) 1求(采)购 2报价
    public function getPublicQuoPur($type,$page=1,$size=10){
        $pubQuoPur = $this->model('purchase')
                    ->where('type='.$type)
                    ->page($page,$size)
                    ->order('input_time desc')
                    ->getPage();
                    return $pubQuoPur;
    }
    //获取资源库数据
    public function getResourceData($type,$page=1,$size=10){
        if($type==1){
            $resData = $this->model('resourcelib')->where('type=0')->page($page,$size)->order('input_time desc')->getPage();
        }elseif ($type==2) {
            $resData = $this->model('resourcelib')->where('type=1')->page($page,$size)->order('input_time desc')->getPage();
        }else{
            $resData = $this->model('resourcelib')->page($page,$size)->order('input_time desc')->getPage();
        }
        return $resData;
    }
    //获取资源库搜索数据
    public function getResSearchData($keywords,$type,$page,$size){
        $where = " realname like '%{$keywords}%' or company like '%{$keywords}%' or content like '%{$keywords}%' ";
        if($type==1){
            $where.=" and type=0 ";
            $searchData = $this->model('resourcelib')->where($where)->page($page,$size)->order('input_time desc')->getPage();
        }elseif ($type==2) {
            $where.=" and type=1 ";
            $searchData = $this->model('resourcelib')->where($where)->page($page,$size)->order('input_time desc')->getPage();
        }else{
            $searchData = $this->model('resourcelib')->where($where)->page($page,$size)->order('input_time desc')->getPage();
        }
        return $searchData;
    }
    //点击委托洽谈保存提交数据
    public function savaComissionData($comData){
        if($this->model('sale_buy')->add($comData)) return true;
        return false;
    }
}