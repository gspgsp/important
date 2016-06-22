<?php
/**
*应用首页模型-app
*/
class mainPageModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'info');
	}
	//获取今日头条、调价动态
	public function getInfos($type){
		if($type == 1){
			return $this->model('info')->order('input_time desc')->limit('0,5')->getAll();
		}elseif($type == 2){
			return $this->model('part_shihua')->order('input_time desc')->limit('0,5')->getAll();
		}
	}
	//获取所有的调价动态
	public function getAllPriceFloor($page=1,$size=20,$sortField='input_time',$sortOrder='desc'){
		$list = $this->model('part_shihua')
			->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
		return $list;
	}
	//获取搜索结果数据(4种方式)
	public function getAllSearchRes($keywords,$page=1,$size=8,$sortField='input_time',$sortOrder='desc'){
		//筛选产品类型
        $p_types = array('1'=>'HDPE','2'=>'LDPE','3'=>'LLDPE','4'=>'PP','5'=>'PVC');
        if(in_array($keywords, $p_types)){
        	$keyValue = $this->_getProKey($p_types,$keywords);
        }
		$where="(fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}')";

            $data = $this->model('purchase')->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
			return $data;
	}
	//获取当前类型的键
    private function _getProKey($p_types,$keywords){
    	foreach ($p_types as $key => $value) {
    		if($value == strtoupper($keywords))
    			return $key;
    	}
    }
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
    public function getOperateRes($p_id){
    	$opres = $this->model('purchase')->where('p_id='.$p_id)->select('id,p_id,user_id,c_id,number,unit_price,provinces')->order('unit_price desc,input_time desc')->limit('0,2')->getAll();
    	foreach ($opres as $key => $value) {
    		$opres[$key]['provinces'] = $this->model('lib_region')->select('name')->where('id='.$opres[$key]['provinces'])->getOne();
    	}
    	return $opres;
    }
    //获取点击查看/委托
    public function getCheckDelegate($otype,$id){

    	$where = "pur.id=$id";
    	$chDeRes = array();
    	$data = $this->model('purchase')->select('pur.id,pur.p_id,pur.number,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.provinces,pur.store_house,pur.c_id,pur.input_time,pur.cargo_type,pur.user_id')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
			->order("$sortField $sortOrder")
			->getRow;
			$contact = M('user:customerContact')->getListByUserid($data['user_id']);
		if($otype == 1){
			//产品信息
			$chDeRes['product_type'] = L('product_type')[$data['product_type']];
			$chDeRes['model'] = $data['model'];
			$chDeRes['unit_price'] = $data['unit_price'];
			$chDeRes['number'] = $data['number'];
			$chDeRes['f_name'] = $data['f_name'];
			$chDeRes['store_house'] = $data['store_house'];
			$chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地
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
			$chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地
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
}