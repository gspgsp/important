<?php
/**
 * 供应商联系人信息管理
 */
class logisticsContactAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('logistics_contact');
		$this->assign('status',L('supplier_contact_type'));  // 联系人用户状态
		$this->doact = sget('do','s');
	}
	/**
	 * 联系人列表
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('logistics_contact.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$filte = sget('filte','i',0);
		//搜索条件
		$where="1";
		//筛选状态
		$status=sget('status','i',0);
		if($status !=0)  $where.=" and `status` =".$status;
		//筛选时间
		$sTime = sget("sTime",'s','create_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
		    $where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['sex']=L('sex')[$v['sex']];
			$list['data'][$k]['is_default']=L('is_default')[$v['is_default']];
			$list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['input_admin'] = M('rbac:adm')->getNameByUser($v['input_admin']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * Ajax删除节点s
	 * @access private
	 */
	private function _remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$res = $this->db->where("id=".$v)->select("is_default")->getRow();
				if($res['is_default']>0){
					$this->error('主联系人不能删除');
				}
			}
		}
		$result=$this->db->where("id in ($ids)")->update(array('status'=>2));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}