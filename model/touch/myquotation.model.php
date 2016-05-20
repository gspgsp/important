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
}