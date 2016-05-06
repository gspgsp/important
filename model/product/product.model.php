<?php 
//产品模型
class productModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'product');
	}
	//根据牌号和厂家id获取商品id
	public function getPidByModel($model='',$fid=0,$id=0){
		$where = "model=$model and f_id=$fid ";
		if($id>0) $where .= " and id !='$id'";
		$result = $this->select('id')->where($where)->getOne();
		return $result>0 ? $result : 0;
	}
	//根据牌号和厂家id创建一条新记录并返回id
	public function insertProduct($data=array()){
		if(!$data) $this->error('添加数据不能为空');
		if($this->add($data)) return $this->getLastID();
		$this->error('数据添加失败');
	}

}