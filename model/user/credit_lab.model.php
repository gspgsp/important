<?php
class credit_labModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'credit_lab');
	}
	//根据分类名称和分类分数获取分类id
	public function getPidByModel($name='',$grade=0,$id=0){
		$where = "lab_name= '$name' and lab_grade=$grade ";
		if($id>0) $where .= " and id !='$id'";
		$result = $this->select('id')->where($where)->getOne();
		return $result>0 ? $result : 0;
	}

	//根据pid获取指定的字段值
	public function getColById($pid=0,$col='id',$value = 'cat_id'){
		$result=$this->select("$col")->where("$value =".$pid)->getOne();
		return $result>0 ? $result : 0;
	}
	//根据名字取得信用分类的id
        public function getIdsByName($name){
		return $this->where("lab_name like '%$name%'")->select('id')->getCol();
	}
}
