<?php
//收付款模型
class collectionModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'collection');
	}

	/*
	 * 获取最后一次操作的数据
	 * @param string $name 检查类型
	 * @param string $value 检查值
	 */
	public function getLastInfo($name='o_id',$value=''){
		$where = "$name='$value'";
		$ids = $this->model('collection')->select ('id')->where($where)->getAll();
		if(!empty($ids)){
			$last_id = max(array_values($ids))['id'];
			$exist=$this->model('collection')->select('*')->where("id= $last_id")->getAll();
		}
		return empty($ids)?false:$exist;
	}
	/*
	 * 获取本次操作以后会受影响的id
	 * @param string $name 检查类型
	 * @param string $value 检查值
	 */
	public function getDiffId($name='o_id',$value='',$id=''){
		$where = "$name='$value'";
		$ids = $this->model('collection')->select ('id')->where($where)->getCol();
		$arr=array();
		foreach ($ids as $v) {
			if ($v>$id) {
				$arr[]=$v;
			}
		}
		return empty($arr)?false:$arr;
	}
}