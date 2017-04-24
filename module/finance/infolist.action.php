<?php
/*
 * 塑料金融
 * 获取所有的申请列表
 * @auth gsp
*/
class infolistAction extends adminBaseAction {
	public function __init(){
		$this->debug=false;
		$this->db = M('public:common')->model('finance_apply');
	}
	public function index(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->display('infolist');
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
		$applys = $this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach ($applys['data'] as $key => $value) {
			$res = json_decode($value['info'],true)[0];
			$res['storage'] = $this->db->model('store')->where("id = {$res['storage']}")->select("store_name")->getOne();
			// $res['factory'] = $this->db->model('factory')->where("fid = {$res['factory']}")->select("f_name")->getOne();
			$res['finance_type'] = $res['finance_type']==1?'塑料代采':($res['finance_type']==2?'塑料白条':($res['finance_type']==3?'仓单融资':''));
			$res['id'] = $value['id'];
			$res['username'] = M('user:customerContact')->getColByName($value['contact_id']);
			$res['mobile'] = M('user:customerContact')->getColByName($value['contact_id'],'mobile');
			$res['c_id'] = $this->db->model('customer')->where("c_id = {$value['c_id']}")->select('c_name')->getOne();
			$res['status'] = $value['status'] == 1?'待处理':($value['status'] == 2?'已转风控':'已拒绝');
			$res['product_type'] = strtoupper($value['product_type']);
			$result[] = $res;
		}
		$this->json_output(array('total'=>$applys['count'],'data'=>$result));
	}


}