<?php
/**
*报价单模型
*/
class myquotationModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	public function changeProductState($id){
		$purchase = $this->model('purchase')->where('id='.$id)->getRow();
		$data['shelve_type']= $purchase['shelve_type'] == 1 ? 2 : 1;
		$result = $this->model('purchase')->where('id='.$id)->update($data);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	//单个更新报价单
	public function refreshCell($id, $qdata){
		$result = $this->model('purchase')->where('id='.$id)->update($qdata);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	//批量更新报价单
	public function refreshMulCell($ids, $up, $down, $del){

	}
}