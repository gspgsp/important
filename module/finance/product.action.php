<?php
/*
 * 金融产品表
 * 获取所有的金融产品表
 * @auth gsp
*/
class productAction extends adminBaseAction {
	public $product;
	public function __init(){
		$this->debug=false;
		$this->db = M('public:common')->model('finance_product');
		$this->product = array(
			'塑料代采',
			'塑料白条',
			'仓单融资'
			);
	}
	public function index(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='add'){ //获取列表
			$this->_add();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}
		$this->display('product');
	}
	/**
	 * 获取塑料金融申请信息
	 * @return [type] [description]
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
		
		$where = "1";
		$products = $this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		$this->json_output(array('total'=>$products['count'],'data'=>$products['data']));
	}
	/**
	 * 新增金融产品
	 * @auth gsp
	 */
	private function _add(){
		$this->ajax = true;
		$this->assign('product',$this->product);
		$this->display('addProduct');
	}
	/**
	 * 新增金融产品类型
	 * @auth gsp
	 */
	public function addProduct(){
		$this->ajax = true;
		$data = sdata();
		if(!empty($data)){
			$data['product_id'] = $data['product_name']+1;
			$data['product_name'] = $this->product[$data['product_name']];
			$data['product_intro'] = trim($data['product_intro']);
			$data['remark'] = trim($data['remark']);
			$data['create_date'] = date('Y-m-d H:i:m',time());
			$data['modify_date'] = date('Y-m-d H:i:m',time());
			$data['create_user'] = 'admin';
		}
		$pro_ids = $this->db->model('finance_product')->select('product_id')->getAll();
		$pro_ids = array_column($pro_ids,'product_id');
		if(in_array($data['product_id'],$pro_ids)) $this->json_output(array('err'=>3,'msg'=>'新增金融产品类型已经存在'));
		if(!$this->db->model('finance_product')->add($data)){
			$this->json_output(array('err'=>2,'msg'=>'新增金融产品类型失败'));
		}else{
			$this->json_output(array('err'=>0,'msg'=>'新增金融产品类型成功'));
		}
	}
	/**
	 * 保存修改
	 * [_save description]
	 * @return [type] [description]
	 */
	private function _save(){
		$this->ajax = true;
		$data = sdata();
		$arr = array(
			'product_name'=>trim($data[0]['product_name']),
			'product_intro'=>trim($data[0]['product_intro']),
			'remark'=>trim($data[0]['remark']),
			'modify_date'=>date('Y-m-d H:i:m',time()),
			'modify_user'=>'admin'
			);
		if(!$this->db->model('finance_product')->where("id = {$data[0]['id']}")->update($arr)){
			$this->json_output(array('err'=>2,'msg'=>'修改金融产品类型失败'));
		}else{
			$this->json_output(array('err'=>0,'msg'=>'修改金融产品类型成功'));
		}
	}
	/**
	 * 删除
	 * @return [type] [description]
	 */
	private function _remove(){
		$this->ajax = true;
		$ids = sget('ids','s');
		if(!$this->db->model('finance_product')->where("id in($ids)")->delete()){
			$this->json_output(array('err'=>2,'msg'=>'删除金融产品类型失败'));
		}else{
			$this->json_output(array('err'=>0,'msg'=>'删除金融产品类型成功'));
		}
	}
}