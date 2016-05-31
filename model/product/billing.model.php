<?php 
//收付款模型
class billingModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'billing');
	}

	/*
	 * 获取最后一次操作的数据
	 * @param string $name 检查类型
	 * @param string $value 检查值
	 */
	public function getLastInfo($name='o_id',$value=''){
		$where = "$name='$value'";
		$ids = $this->model('billing')->select ('id')->where($where)->getAll();
		if(!empty($ids)){
			$last_id = max(array_values($ids))['id'];
			$exist=$this->model('billing')->select('*')->where("id= $last_id")->getAll();
		}
		return empty($ids)?false:$exist;
	}
}