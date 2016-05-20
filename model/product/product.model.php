<?php 
//产品模型
class productModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'product');
	}
	//根据牌号和厂家id获取商品id
	public function getPidByModel($model='',$fid=0,$id=0){
		$where = "model= '$model' and f_id=$fid ";
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
	//根据fid获取指定的字段值
	public function getColById($fid=0,$col='id'){
		$result=$this->select("$col")->where('f_id='.$fid)->getOne();
		return $result>0 ? $result : 0;
	}
	//根据pid获取指定的字段值
	public function getModelById($pid=0,$col='model'){
		$result=$this->select("$col")->where('id='.$pid)->getOne();
		return $result != '' ? $result : '-';
	}
	//根据商品id获取商品的厂家名字
	public function getFnameByPid($pid=0){
		return $this->select("f.f_name, p.*")->from('product p')->join('factory f','f.fid=p.f_id')->where('p.id='.$pid)->getRow();
	}
	/**
	 * 模糊查询牌号匹配的明细
	 */
	public function getpidByPname($value=''){
		$arr=$this->select('id')->where("model like '%".$value."%'")->getAll();
		p($arr);
		foreach ($arr as $key => $v) {
			$ids[]=$v['id'];
		}
		$data = implode(',',$ids);

		return empty($data)? false : $data;
	}

}