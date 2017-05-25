<?php
/**
*报价单模型
*/
class myquotationModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	//单个改变状态
	public function changeProductState($id){
		$purchase = $this->model('purchase')->where('id='.$id)->getRow();
		$data['shelve_type']= $purchase['shelve_type'] == 1 ? 2 : 1;
		$result = $this->model('purchase')->where('id='.$id)->update($data);
		if($result){
			return array('err'=>0,'msg'=>'切换成功');
		}else{
			return array('err'=>1,'msg'=>'切换失败');
		}
	}
	//单个更新报价单
	public function refreshCell($id, $data, $p_id){
		if(!empty($id)){
			$fdata = array();
			$qdata = array();
			//$data实际上只有两条数据unit_price和number
			foreach ($data as $key => $value) {
				if($key == 'f_name'){
					$fdata['f_name'] = $value;
				}else{
					$qdata[$key] = $value;
				}
			}
			$pur = $this->model('purchase')->where('id='.$id)->getRow();
			if($data['number'] == $pur['number'] && $data['unit_price'] == $pur['unit_price']) return array('err'=>2,'msg'=>'没有更新的数据');

			$qdata['p_id'] = $pur['p_id'];
			$qdata['user_id'] = $pur['user_id'];
			$qdata['c_id'] = $pur['c_id'];
			$qdata['unit'] = $pur['unit'];
			$qdata['number'] = $data['number'];
			$qdata['unit_price'] = $data['unit_price'];
			$qdata['price_type'] = $pur['price_type'];
			$qdata['package'] = $pur['package'];
			$qdata['area'] = $pur['area'];
			$qdata['provinces'] = $pur['provinces'];
			$qdata['supply_count'] = $pur['supply_count'];
			$qdata['origin'] = $pur['origin'];
			$qdata['store_house'] = $pur['store_house'];
			$qdata['cargo_type'] = $pur['cargo_type'];
			$qdata['period'] = $pur['period'];
			$qdata['content'] = $pur['content'];
			$qdata['customer_manager'] = $pur['customer_manager'];
			$qdata['status'] = $pur['status'];
			$qdata['give_time'] = $pur['give_time'];
			$qdata['chk_time'] = $pur['chk_time'];
			$qdata['is_erp'] = $pur['is_erp'];
			$qdata['remark'] = $pur['remark'];
			$qdata['group_no'] = $pur['group_no'];
			$qdata['is_vip'] = $pur['is_vip'];
			$qdata['bargain'] = $pur['bargain'];
			$qdata['input_time'] = CORE_TIME;
			$qdata['input_admin'] = $pur['input_admin'];
			$qdata['update_time'] = CORE_TIME;
			$qdata['update_admin'] = $pur['update_admin'];
			$qdata['type'] = $pur['type'];
			$qdata['shelve_type'] = $pur['shelve_type'];
			$qdata['last_buy_sale'] = $pur['last_buy_sale'];
			$qdata['is_union'] = $pur['is_union'];
			$result = $this->model('purchase')->add($qdata);
			if($result){
				return array('err'=>0,'msg'=>'更新成功');
			}else{
				return array('err'=>1,'msg'=>'更新失败');
			}
		}

	}
	//批量更新报价单
	public function refreshMulCell($ids, $up, $down, $del){
		if ($up == 1) {
			$result = $this->_mulUp($ids);
			if($result){
				return array('err'=>0,'msg'=>'批量上架成功');
			}else{
				return array('err'=>1,'msg'=>'批量上架失败');
			}
		}elseif ($down == 1) {
			$result = $this->_mulDown($ids);
			if($result){
				return array('err'=>0,'msg'=>'批量下架成功');
			}else{
				return array('err'=>1,'msg'=>'批量下架失败');
			}
		}elseif ($del == 1) {
			$result = $this->_mulDelete($ids);
			if($result){
				return array('err'=>0,'msg'=>'批量删除成功');
			}else{
				return array('err'=>1,'msg'=>'批量删除失败');
			}
		}
	}
	//批量上架
	private function _mulUp($ids){
		foreach ($ids as $id) {
			if(!empty($id)){
				$purchase = $this->model('purchase')->where('id='.$id)->getRow();
				$data['shelve_type']= 1;
				$result = $this->model('purchase')->where('id='.$id)->update($data);
			}
		}
		return $result;
	}
	//批量下架
	private function _mulDown($ids){
		foreach ($ids as $id) {
			if(!empty($id)){
				$purchase = $this->model('purchase')->where('id='.$id)->getRow();
				$data['shelve_type']= 2;
				$result = $this->model('purchase')->where('id='.$id)->update($data);
			}
		}
		return $result;
	}
	//批量删除
	private function _mulDelete($ids){
		foreach ($ids as $id) {
			if(!empty($id)){
				$result = $this->model('purchase')->where('id='.$id)->delete();
			}
		}
		return $result;
	}
}