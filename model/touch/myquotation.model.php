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
			foreach ($data as $key => $value) {
				if($key == 'f_name'){
					$fdata['f_name'] = $value;
				}else{
					$qdata[$key] = $value;
				}
			}
			$f_id = $this->model('product')->select('f_id')->where('id='.$p_id)->getOne();
			$this->model('factory')->where('fid='.$f_id)->update($fdata);
			$result = $this->model('purchase')->where('id='.$id)->update($qdata);
		}
		if($result){
			return array('err'=>0,'msg'=>'更新成功');
		}else{
			return array('err'=>1,'msg'=>'更新失败');
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