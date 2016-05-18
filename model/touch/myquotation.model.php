<?php
/**
*报价单模型
*/
class myquotationModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'product');
	}
	public function changeProductState($p_id){
		$product = $this->model('product')->where('id='.$p_id)->getRow();
		$data['status']= $product['status'] == 1 ? 2 : 1;
		$result = $this->model('product')->where('id='.$p_id)->update($data);
		if($result){
			return true;
		}else{
			return false;
		}
	}
}