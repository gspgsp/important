<?php
/**
*仓库管理控制器
*/
class storeproductAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('store_product');
	}
	public function init(){
		//获取列表数据
		$doact=sget('do','s');
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			//搜索条件
			$where=" 1 ";
			//关键词
			$key_type=sget('key_type','s','s_name');
			$keyword=sget('keyword','s');

			if(!empty($keyword)){
				if ($key_type == 's_name') {
					$sid=M('product:store')->getSidBySname("$keyword");
					$where.=" and `s_id` in ($sid)";
				}
				if ($key_type == 'f_name') {
					$ids=M('product:factory')->getIdsByName("$keyword");
					if(empty($ids)) $this->error('查询不存在');
					$data = implode(',',$ids);
					$m=$this->db->model('product')->select('id')->where("`f_id` in ($data)")->getAll();
					if(empty($m)) $this->error('查询不存在');
					foreach ($m as $v) {
						$data2[]=$v['id'];
					}
					$_id = implode(',',$data2);
					$where.=" and `p_id` in ($_id)";
				}
				if ($key_type == 'model') {
					$pid=M('product:product')->getpidByPname("$keyword");
					$where.=" and `p_id` in ($pid)";
				}
			}
			$list=$this->db->from('store_product sp')
			->join('product pro','sp.p_id=pro.id')
			->join('store s','s.id=sp.s_id')
			->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->select('sp.*,pro.model,s.store_name')
			->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
				$list['data'][$k]['f_name']=M('product:product')->getFnameByPid($v['p_id'])['f_name'];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('doact',$doact);
		$this->assign('page_title','仓库货品管理');
		$this->display('storeProduct.list.html');
	}

	/**
	 * 保存库存调整信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}
		$id = sget('spid','i');
		p($data);
		p($id);die;
		if(!M('product:store')->curUnique('store_name',$data['store_name'])) $this->error('仓库名重复');
		if(!M('product:store')->curUnique('store_tel',$data['store_tel'])) $this->error('仓库电话重复');
		$data = $data+array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		$result = $this->db->add($data);
		if(!$result) $this->error('操作失败');
		$cache=cache::startMemcache();
		$cache->delete('store');
		$this->success('操作成功');
	}

	
}