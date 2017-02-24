<?php
class credit_catModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'credit_cat');
	}
	//根据分类名称获取id
	public function getPidByModel($name='',$grade=0,$id=0){
		$where = "catname= '$name' ";
		if($id>0) $where .= " and id !='$id'";
		$result = $this->select('id')->where($where)->getOne();
		return $result>0 ? $result : 0;
	}
        //根据名字取得信用分类的id
        public function getIdsByName($name){
		return $this->where("catname like '%$name%'")->select('id')->getCol();
	}
}

