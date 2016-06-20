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

            $data = $this->db->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
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
    		$sortField = '';
    		$sortOrder = 'desc';
    	}elseif ($stype == 2) {
    		# code...
    	}elseif ($stype == 3) {
    		# code...
    	}
    	$data = $this->db->select('pur.id,pur.p_id,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->page($page,$size)
			->order("$sortField $sortOrder")
			->getPage();
			return $data;
    }
}